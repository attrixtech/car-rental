<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\PickupLocation;
use App\Models\Booking;
use App\Services\RentalOperationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RentalWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_complete_self_drive_lifecycle_workflow(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->actingAs($admin);

        $customer = Customer::where('is_dl_verified', true)->first();
        $category = VehicleCategory::where('slug', 'hatchback')->first();
        $vehicle = Vehicle::where('category_id', $category->id)->where('status', 'available')->first();
        $selfDriveType = RentalType::where('code', 'self_drive')->first();
        $location = PickupLocation::first();

        $start = Carbon::now()->addDays(1)->setTime(10, 0);
        $end = Carbon::now()->addDays(3)->setTime(10, 0); // 2 days

        // 1. Create Booking
        $response = $this->post(route('bookings.store'), [
            'customer_id' => $customer->id,
            'rental_type_id' => $selfDriveType->id,
            'vehicle_category_id' => $category->id,
            'assigned_vehicle_id' => $vehicle->id,
            'pickup_location_id' => $location->id,
            'return_location_id' => $location->id,
            'pickup_datetime' => $start->toDateTimeString(),
            'return_datetime' => $end->toDateTimeString(),
            'notes' => 'Self-drive test booking',
        ]);

        $response->assertSessionHasNoErrors();
        $booking = Booking::where('customer_id', $customer->id)->orderBy('id', 'desc')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('allocated', $booking->booking_status);

        // 2. Verify Escrow Security Deposit is held
        $deposit = $booking->deposit;
        $this->assertNotNull($deposit, 'Security deposit record must be initialized for self-drive.');
        $this->assertEquals('held', $deposit->status);
        $this->assertEquals(4000.00, (float) $deposit->deposit_amount);

        // 3. Perform Vehicle Handover (Dispatch)
        $startOdometer = $vehicle->current_odometer;
        $handoverResponse = $this->post(route('operations.handover.submit', $booking->id), [
            'odometer_start' => $startOdometer,
            'fuel_level_start' => 8.00, // full tank
            'cleanliness_interior' => 1,
            'cleanliness_exterior' => 1,
            'spare_tyre_present' => 1,
            'jack_tools_present' => 1,
            'documents_in_car' => 1,
            'notes' => 'Baseline vehicle inspected, pristine condition.',
        ]);

        $handoverResponse->assertSessionHasNoErrors();
        $booking->refresh();
        $vehicle->refresh();

        $this->assertEquals('active', $booking->booking_status);
        $this->assertEquals('rented', $vehicle->status);
        $this->assertNotNull($booking->handover);

        // 4. Perform Vehicle Return (Check-In) with 100 excess km and a bumper scratch
        $endOdometer = $startOdometer + 600; // 500 free km, so 100 excess km
        $returnResponse = $this->post(route('operations.return.submit', $booking->id), [
            'odometer_end' => $endOdometer,
            'fuel_level_end' => 8.00,
            'cleaning_required' => 0,
            'damages' => [
                [
                    'body_location' => 'rear_bumper',
                    'severity' => 'minor',
                    'description' => 'Minor bumper scuff during parking',
                    'estimated_repair_cost' => 1200.00,
                ]
            ],
            'notes' => 'Customer acknowledged scratch on rear bumper.',
        ]);

        $returnResponse->assertSessionHasNoErrors();
        $booking->refresh();
        $vehicle->refresh();

        // 5. Verify Settlement and Status
        $this->assertEquals('settled', $booking->booking_status);
        $this->assertEquals('available', $vehicle->status);
        $this->assertEquals($endOdometer, $vehicle->current_odometer);

        $settlement = $booking->settlement;
        $this->assertNotNull($settlement);
        $this->assertEquals(1100.00, (float) $settlement->excess_km_charged); // 100 excess km * ₹11/km
        $this->assertEquals(1200.00, (float) $settlement->damage_charges); // ₹1,200 bumper repair

        // 6. Verify Escrow Deposit Deductions & Refund
        $deposit->refresh();
        $this->assertEquals('partially_deducted', $deposit->status);
        // Total deductions = 1100 + 1200 = 2300.
        // Deposit was 4000. Refund due = 4000 - 2300 = 1700.
        $this->assertEquals(2300.00, (float) $deposit->deductions_amount);
        $this->assertEquals(1700.00, (float) $deposit->refunded_amount);

        // 7. Verify Tax Invoice Generated
        $invoice = $booking->invoices()->first();
        $this->assertNotNull($invoice);
        $this->assertGreaterThan(0, $invoice->items()->count());
    }
}

