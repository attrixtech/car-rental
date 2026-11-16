<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\PickupLocation;
use App\Services\VehicleAvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VehicleAvailabilityTest extends TestCase
{
    use DatabaseTransactions;

    protected VehicleAvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VehicleAvailabilityService();
    }

    public function test_vehicle_is_available_when_no_conflicts(): void
    {
        $vehicle = Vehicle::where('status', 'available')->first();
        $this->assertNotNull($vehicle);

        $start = Carbon::now()->addDays(5);
        $end = Carbon::now()->addDays(7);

        $isAvailable = $this->service->isVehicleAvailable($vehicle->id, $start, $end);
        $this->assertTrue($isAvailable);
    }

    public function test_vehicle_is_not_available_when_overlapping_booking_exists(): void
    {
        $vehicle = Vehicle::where('status', 'available')->first();
        $customer = Customer::first();
        $rentalType = RentalType::first();
        $location = PickupLocation::first();
        $category = VehicleCategory::first();

        $start = Carbon::now()->addDays(10);
        $end = Carbon::now()->addDays(12);

        // Create confirmed booking
        Booking::create([
            'company_id' => $vehicle->company_id,
            'branch_id' => $vehicle->branch_id,
            'booking_number' => 'TEST-CONF-001',
            'customer_id' => $customer->id,
            'rental_type_id' => $rentalType->id,
            'vehicle_category_id' => $category->id,
            'assigned_vehicle_id' => $vehicle->id,
            'pickup_location_id' => $location->id,
            'return_location_id' => $location->id,
            'pickup_datetime' => $start,
            'return_datetime' => $end,
            'booking_status' => 'confirmed',
            'total_estimated_amount' => 4500,
        ]);

        // Attempt overlapping reservation
        $overlapStart = Carbon::now()->addDays(11);
        $overlapEnd = Carbon::now()->addDays(13);

        $isAvailable = $this->service->isVehicleAvailable($vehicle->id, $overlapStart, $overlapEnd);
        $this->assertFalse($isAvailable, 'Vehicle must not be available during overlapping booking.');
    }

    public function test_turnaround_buffer_time_is_enforced(): void
    {
        $vehicle = Vehicle::where('status', 'available')->first();
        $customer = Customer::first();
        $rentalType = RentalType::first();
        $location = PickupLocation::first();
        $category = VehicleCategory::first();

        $start = Carbon::now()->addDays(20);
        $end = Carbon::now()->addDays(22)->setTime(10, 0); // ends at 10:00 AM

        Booking::create([
            'company_id' => $vehicle->company_id,
            'branch_id' => $vehicle->branch_id,
            'booking_number' => 'TEST-BUF-001',
            'customer_id' => $customer->id,
            'rental_type_id' => $rentalType->id,
            'vehicle_category_id' => $category->id,
            'assigned_vehicle_id' => $vehicle->id,
            'pickup_location_id' => $location->id,
            'return_location_id' => $location->id,
            'pickup_datetime' => $start,
            'return_datetime' => $end,
            'booking_status' => 'confirmed',
            'total_estimated_amount' => 4500,
        ]);

        // Request next booking at 11:00 AM (only 1 hour after return, but 2h buffer required)
        $nextStart = Carbon::now()->addDays(22)->setTime(11, 0);
        $nextEnd = Carbon::now()->addDays(23);

        $isAvailable = $this->service->isVehicleAvailable($vehicle->id, $nextStart, $nextEnd, 2);
        $this->assertFalse($isAvailable, 'Turnaround buffer of 2 hours must block allocation.');

        // Request next booking at 13:00 PM (3 hours after return, buffer cleared)
        $validStart = Carbon::now()->addDays(22)->setTime(13, 0);
        $isAvailableAfterBuffer = $this->service->isVehicleAvailable($vehicle->id, $validStart, $nextEnd, 2);
        $this->assertTrue($isAvailableAfterBuffer, 'Vehicle must be available once buffer window expires.');
    }
}

