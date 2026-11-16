<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\VehicleHandover;
use App\Models\VehicleReturn;
use App\Models\VehicleDamageReport;
use App\Models\RentalSettlement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalOperationService
{
    /**
     * Complete Vehicle Handover (Dispatch vehicle to customer).
     */
    public function processHandover(Booking $booking, array $data, int $officerId): VehicleHandover
    {
        return DB::transaction(function () use ($booking, $data, $officerId) {
            $handover = VehicleHandover::create([
                'booking_id' => $booking->id,
                'vehicle_id' => $booking->assigned_vehicle_id,
                'officer_id' => $officerId,
                'customer_id' => $booking->customer_id,
                'handover_datetime' => $data['handover_datetime'] ?? now(),
                'odometer_start' => $data['odometer_start'],
                'fuel_level_start' => $data['fuel_level_start'] ?? 8.00,
                'cleanliness_interior' => $data['cleanliness_interior'] ?? true,
                'cleanliness_exterior' => $data['cleanliness_exterior'] ?? true,
                'spare_tyre_present' => $data['spare_tyre_present'] ?? true,
                'jack_tools_present' => $data['jack_tools_present'] ?? true,
                'documents_in_car' => $data['documents_in_car'] ?? true,
                'customer_signature' => $data['customer_signature'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Mark booking as active
            $booking->update([
                'booking_status' => 'active',
                'actual_pickup_datetime' => $handover->handover_datetime,
            ]);

            // Mark vehicle as rented
            $booking->assignedVehicle?->update([
                'status' => 'rented',
                'current_odometer' => $data['odometer_start'],
                'current_fuel_level' => $data['fuel_level_start'] ?? 8.00,
            ]);

            // If chauffeur-driven, mark driver on_trip
            if ($booking->assignedDriver) {
                $booking->assignedDriver->update(['status' => 'on_trip']);
            }

            return $handover;
        });
    }

    /**
     * Complete Vehicle Return (Check-in vehicle, calculate excess km, fuel shortfall, late fees, and damages).
     */
    public function processReturn(Booking $booking, array $data, int $officerId): array
    {
        return DB::transaction(function () use ($booking, $data, $officerId) {
            $handover = $booking->handover;
            $startKm = $handover ? $handover->odometer_start : ($booking->assignedVehicle->current_odometer ?? 0);
            $endKm = (int) $data['odometer_end'];
            $totalKmDriven = max(0, $endKm - $startKm);

            $fuelStart = $handover ? (float) $handover->fuel_level_start : 8.00;
            $fuelEnd = (float) ($data['fuel_level_end'] ?? 8.00);

            // Compute Excess Km
            $category = $booking->category;
            $freeKm = $booking->base_rental_amount > 0 ? ($category->free_km_per_day * ceil(max(1, $booking->pickup_datetime->diffInHours($booking->return_datetime)) / 24)) : 250;
            $excessKm = max(0, $totalKmDriven - $freeKm);
            $excessKmCharge = $excessKm * (float) ($category->excess_km_rate ?? 12.00);

            // Compute Late Hours (with 60-min grace period)
            $scheduledReturn = Carbon::parse($booking->return_datetime);
            $actualReturn = Carbon::parse($data['return_datetime'] ?? now());
            $lateHours = 0;
            if ($actualReturn->gt($scheduledReturn->copy()->addMinutes(60))) {
                $lateHours = ceil($scheduledReturn->diffInMinutes($actualReturn) / 60);
            }
            $lateHourCharge = $lateHours * (float) ($category->excess_hour_rate ?? 150.00);

            // Compute Fuel Shortfall
            $fuelShortfallUnits = max(0, $fuelStart - $fuelEnd); // in 1/8 units
            $tankCapacity = (float) ($booking->assignedVehicle->fuel_tank_capacity ?? 45.00);
            $fuelShortfallLitres = ($fuelShortfallUnits / 8.0) * $tankCapacity;
            $fuelPricePerLitre = 105.00; // Diesel/Petrol standard price in INR
            $fuelShortfallCharge = round(($fuelShortfallLitres * $fuelPricePerLitre), 2);

            // Cleaning Fee
            $cleaningFee = !empty($data['cleaning_required']) ? 500.00 : 0.00;

            // Damage Reports
            $damageChargeTotal = 0.00;
            if (!empty($data['damages']) && is_array($data['damages'])) {
                foreach ($data['damages'] as $dmg) {
                    if (!empty($dmg['description'])) {
                        $cost = (float) ($dmg['estimated_repair_cost'] ?? 0);
                        VehicleDamageReport::create([
                            'booking_id' => $booking->id,
                            'vehicle_id' => $booking->assigned_vehicle_id,
                            'report_type' => 'return_inspection',
                            'severity' => $dmg['severity'] ?? 'minor',
                            'body_location' => $dmg['body_location'] ?? 'exterior',
                            'description' => $dmg['description'],
                            'estimated_repair_cost' => $cost,
                            'is_customer_liable' => true,
                        ]);
                        $damageChargeTotal += $cost;
                    }
                }
            }

            // Create Return Record
            $vehicleReturn = VehicleReturn::create([
                'booking_id' => $booking->id,
                'vehicle_id' => $booking->assigned_vehicle_id,
                'receiving_officer_id' => $officerId,
                'customer_id' => $booking->customer_id,
                'return_datetime' => $actualReturn,
                'odometer_end' => $endKm,
                'fuel_level_end' => $fuelEnd,
                'excess_km' => $excessKm,
                'late_hours' => $lateHours,
                'cleaning_required' => !empty($data['cleaning_required']),
                'cleaning_charge' => $cleaningFee,
                'fuel_shortfall_litres' => $fuelShortfallLitres,
                'fuel_shortfall_charge' => $fuelShortfallCharge,
                'customer_signature' => $data['customer_signature'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Release vehicle & driver
            $booking->assignedVehicle?->update([
                'status' => 'available',
                'current_odometer' => $endKm,
                'current_fuel_level' => $fuelEnd,
            ]);

            if ($booking->assignedDriver) {
                $booking->assignedDriver->update(['status' => 'available']);
            }

            // Calculate Final Gross Settlement
            $baseRental = (float) $booking->base_rental_amount;
            $taxAmount = (float) $booking->tax_amount;
            $totalAdditionalCharges = $excessKmCharge + $lateHourCharge + $fuelShortfallCharge + $cleaningFee + $damageChargeTotal;
            
            // Tax on additional charges
            $additionalTax = round($totalAdditionalCharges * 0.18, 2);
            $grossFinal = $baseRental + $taxAmount + $totalAdditionalCharges + $additionalTax;

            $deposit = $booking->deposit;
            $depositHeld = $deposit ? (float) $deposit->deposit_amount : 0.00;

            // Deduct damages, excess km, late fees from deposit
            $depositDeductions = min($depositHeld, $totalAdditionalCharges);
            $depositRefund = max(0, $depositHeld - $totalAdditionalCharges);
            $balancePayable = max(0, $grossFinal - ($baseRental + $taxAmount + $depositDeductions));

            // Create Settlement record
            $settlement = RentalSettlement::create([
                'booking_id' => $booking->id,
                'base_rental_charged' => $baseRental,
                'excess_km_charged' => $excessKmCharge,
                'late_hours_charged' => $lateHourCharge,
                'fuel_deficit_charged' => $fuelShortfallCharge + $cleaningFee,
                'damage_charges' => $damageChargeTotal,
                'toll_parking_charges' => 0.00,
                'discount_applied' => (float) $booking->discount_amount,
                'tax_charged' => $taxAmount + $additionalTax,
                'gross_final_amount' => $grossFinal,
                'advance_paid' => $baseRental + $taxAmount,
                'deposit_held' => $depositHeld,
                'deposit_deductions' => $depositDeductions,
                'deposit_refund_amount' => $depositRefund,
                'balance_payable_by_customer' => $balancePayable,
                'settled_by' => $officerId,
                'settled_at' => now(),
            ]);

            // Update Deposit record
            if ($deposit) {
                $deposit->update([
                    'deductions_amount' => $depositDeductions,
                    'refunded_amount' => $depositRefund,
                    'refunded_at' => now(),
                    'status' => $depositDeductions > 0 ? 'partially_deducted' : 'refunded',
                ]);
            }

            // Update Booking status
            $booking->update([
                'booking_status' => 'settled',
                'actual_return_datetime' => $actualReturn,
            ]);

            return [
                'return' => $vehicleReturn,
                'settlement' => $settlement,
                'excess_km' => $excessKm,
                'excess_km_charge' => $excessKmCharge,
                'late_hours' => $lateHours,
                'late_hour_charge' => $lateHourCharge,
                'fuel_shortfall_litres' => $fuelShortfallLitres,
                'fuel_shortfall_charge' => $fuelShortfallCharge,
                'damage_charge_total' => $damageChargeTotal,
                'deposit_refund' => $depositRefund,
                'balance_payable' => $balancePayable,
            ];
        });
    }
}

