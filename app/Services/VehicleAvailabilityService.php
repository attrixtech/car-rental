<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\VehicleMaintenance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class VehicleAvailabilityService
{
    /**
     * Check if a specific vehicle is available between start and end times (including buffer).
     */
    public function isVehicleAvailable(int $vehicleId, Carbon|string $start, Carbon|string $end, int $bufferHours = 2, ?int $ignoreBookingId = null): bool
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $bufferedEnd = (clone $end)->addHours($bufferHours);

        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle || in_array($vehicle->status, ['maintenance', 'in_repair', 'decommissioned'])) {
            return false;
        }

        // Check overlapping bookings
        $hasBookingConflict = Booking::where('assigned_vehicle_id', $vehicleId)
            ->whereIn('booking_status', ['confirmed', 'allocated', 'active'])
            ->when($ignoreBookingId, fn($q) => $q->where('id', '!=', $ignoreBookingId))
            ->where(function ($query) use ($start, $bufferedEnd, $bufferHours) {
                // Overlap: max(start1, start2) < min(end1, end2)
                $query->where('pickup_datetime', '<', $bufferedEnd)
                      ->whereRaw('DATE_ADD(return_datetime, INTERVAL ? HOUR) > ?', [$bufferHours, $start]);
            })
            ->exists();

        if ($hasBookingConflict) {
            return false;
        }

        // Check scheduled maintenance
        $hasMaintenanceConflict = VehicleMaintenance::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where(function ($query) use ($start, $end) {
                $query->whereDate('scheduled_date', '>=', $start->toDateString())
                      ->whereDate('scheduled_date', '<=', $end->toDateString());
            })
            ->exists();

        return !$hasMaintenanceConflict;
    }

    /**
     * Get all available vehicles for a given category and time window.
     */
    public function getAvailableVehicles(?int $categoryId, Carbon|string $start, Carbon|string $end, ?int $branchId = null, int $bufferHours = 2)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $bufferedEnd = (clone $end)->addHours($bufferHours);

        return Vehicle::with(['category', 'model.brand', 'branch'])
            ->where('status', 'available')
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDoesntHave('bookings', function (Builder $query) use ($start, $bufferedEnd, $bufferHours) {
                $query->whereIn('booking_status', ['confirmed', 'allocated', 'active'])
                      ->where('pickup_datetime', '<', $bufferedEnd)
                      ->whereRaw('DATE_ADD(return_datetime, INTERVAL ? HOUR) > ?', [$bufferHours, $start]);
            })
            ->whereDoesntHave('maintenanceRecords', function (Builder $query) use ($start, $end) {
                $query->whereIn('status', ['scheduled', 'in_progress'])
                      ->whereDate('scheduled_date', '>=', $start->toDateString())
                      ->whereDate('scheduled_date', '<=', $end->toDateString());
            })
            ->get();
    }
}

