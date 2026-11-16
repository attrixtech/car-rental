<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Customer;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\PickupLocation;
use App\Models\Booking;
use App\Models\TripRecord;
use App\Models\TripExpense;
use App\Models\DriverAllowance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChauffeurDrivenWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_complete_chauffeur_driven_outstation_lifecycle(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->actingAs($admin);

        $customer = Customer::first();
        $category = VehicleCategory::where('slug', 'premium-muv')->first();
        $vehicle = Vehicle::where('category_id', $category->id)->where('status', 'available')->first();
        $driver = Driver::where('status', 'available')->first();
        $chauffeurRentalType = RentalType::where('code', 'chauffeur_driven')->first();
        $location = PickupLocation::first();

        $start = Carbon::now()->addDays(2)->setTime(8, 0);
        $end = Carbon::now()->addDays(4)->setTime(20, 0); // 2 full days

        // 1. Create Chauffeur-Driven Booking
        $response = $this->post(route('bookings.store'), [
            'customer_id' => $customer->id,
            'rental_type_id' => $chauffeurRentalType->id,
            'vehicle_category_id' => $category->id,
            'assigned_vehicle_id' => $vehicle->id,
            'assigned_driver_id' => $driver->id,
            'pickup_location_id' => $location->id,
            'return_location_id' => $location->id,
            'pickup_datetime' => $start->toDateTimeString(),
            'return_datetime' => $end->toDateTimeString(),
            'notes' => 'Outstation corporate trip to Pune with chauffeur',
        ]);

        $response->assertSessionHasNoErrors();
        $booking = Booking::where('customer_id', $customer->id)->orderBy('id', 'desc')->first();
        $this->assertNotNull($booking);
        $this->assertEquals($driver->id, $booking->assigned_driver_id);
        $this->assertTrue($booking->isChauffeurDriven());

        // 2. Perform Dispatch Handover
        $startKm = $vehicle->current_odometer;
        $handoverResponse = $this->post(route('operations.handover.submit', $booking->id), [
            'odometer_start' => $startKm,
            'fuel_level_start' => 8.00,
            'cleanliness_interior' => 1,
            'cleanliness_exterior' => 1,
            'spare_tyre_present' => 1,
            'jack_tools_present' => 1,
            'documents_in_car' => 1,
            'notes' => 'Chauffeur Ramesh Patil dispatched on duty slip.',
        ]);

        $handoverResponse->assertSessionHasNoErrors();
        $booking->refresh();
        $driver->refresh();

        $this->assertEquals('active', $booking->booking_status);
        $this->assertEquals('on_trip', $driver->status);

        // 3. Log Chauffeur Duty Slip Telemetry & Night Batas
        $tripRecord = TripRecord::create([
            'booking_id' => $booking->id,
            'driver_id' => $driver->id,
            'trip_start_datetime' => $start,
            'garage_start_km' => $startKm,
            'trip_end_datetime' => $end,
            'garage_end_km' => $startKm + 620,
            'total_trip_km' => 620,
            'night_halts' => 2,
            'toll_parking_amount' => 450.00,
            'driver_bata_amount' => 700.00, // 2 days bata
            'customer_rating' => 5,
            'customer_comments' => 'Driver was courteous, highly punctual, and smooth highway driving.',
        ]);

        TripExpense::create([
            'trip_record_id' => $tripRecord->id,
            'expense_type' => 'toll',
            'amount' => 450.00,
            'receipt_number' => 'MUM-PUN-EXPWY-8812',
        ]);

        DriverAllowance::create([
            'driver_id' => $driver->id,
            'booking_id' => $booking->id,
            'allowance_type' => 'night_bata',
            'amount' => 700.00,
            'duty_date' => $start->toDateString(),
            'is_paid' => true,
            'paid_at' => now(),
        ]);

        // 4. Return Vehicle & Complete Settlement
        $endKm = $startKm + 620;
        $returnResponse = $this->post(route('operations.return.submit', $booking->id), [
            'odometer_end' => $endKm,
            'fuel_level_end' => 8.00,
            'cleaning_required' => 0,
            'notes' => 'Trip ended successfully at garage.',
        ]);

        $returnResponse->assertSessionHasNoErrors();
        $booking->refresh();
        $driver->refresh();
        $vehicle->refresh();

        $this->assertEquals('settled', $booking->booking_status);
        $this->assertEquals('available', $driver->status);
        $this->assertEquals('available', $vehicle->status);
        $this->assertEquals($endKm, $vehicle->current_odometer);
    }
}

