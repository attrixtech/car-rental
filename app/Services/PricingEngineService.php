<?php

namespace App\Services;

use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\RentalRatePlan;
use App\Models\RentalPackage;
use Carbon\Carbon;

class PricingEngineService
{
    /**
     * Compute full price breakdown for booking.
     */
    public function calculateRentalEstimate(
        int $categoryId,
        int $rentalTypeId,
        Carbon|string $pickupDatetime,
        Carbon|string $returnDatetime,
        ?int $packageId = null,
        float $discountPercent = 0.0,
        bool $isInterstate = false
    ): array {
        $start = Carbon::parse($pickupDatetime);
        $end = Carbon::parse($returnDatetime);
        $totalHours = max(1, $start->diffInHours($end));
        $totalDays = (int) ceil($totalHours / 24);

        $category = VehicleCategory::findOrFail($categoryId);
        $rentalType = RentalType::findOrFail($rentalTypeId);

        $baseAmount = 0.00;
        $driverAllowance = 0.00;
        $securityDeposit = (float) $category->security_deposit;
        $freeKm = $category->free_km_per_day * max(1, $totalDays);

        if ($packageId) {
            $package = RentalPackage::find($packageId);
            if ($package) {
                $baseAmount = (float) $package->base_price;
                $freeKm = $package->included_km;
            }
        } else {
            // Find rate plan or fallback to category rates
            $ratePlan = RentalRatePlan::where('category_id', $categoryId)
                ->where('rental_type_id', $rentalTypeId)
                ->where('is_active', true)
                ->first();

            if ($totalDays >= 7 && $ratePlan?->billing_cycle === 'weekly') {
                $weeks = ceil($totalDays / 7);
                $baseAmount = $weeks * (float) $ratePlan->base_rate;
            } elseif ($totalHours <= 12 && $ratePlan?->billing_cycle === 'hourly') {
                $baseAmount = $totalHours * (float) $ratePlan->base_rate;
            } else {
                $dailyRate = $ratePlan ? (float) $ratePlan->base_rate : (float) $category->base_daily_rate;
                $baseAmount = max(1, $totalDays) * $dailyRate;
            }
        }

        // Chauffeur allowance if chauffeur-driven
        if ($rentalType->is_chauffeur_included) {
            $driverAllowance = max(1, $totalDays) * 400.00; // standard day bata
        }

        $subtotal = $baseAmount + $driverAllowance;
        $discountAmount = round(($subtotal * ($discountPercent / 100)), 2);
        $taxableAmount = max(0, $subtotal - $discountAmount);

        // GST Calculation
        $gstPercent = (float) $rentalType->gst_rate_percent; // 18% for self-drive, 5% for chauffeur
        $totalGst = round(($taxableAmount * ($gstPercent / 100)), 2);

        $cgst = 0.00;
        $sgst = 0.00;
        $igst = 0.00;

        if ($isInterstate) {
            $igst = $totalGst;
        } else {
            $cgst = round($totalGst / 2, 2);
            $sgst = round($totalGst - $cgst, 2);
        }

        $totalEstimated = $taxableAmount + $totalGst;

        return [
            'total_hours' => $totalHours,
            'total_days' => max(1, $totalDays),
            'free_km' => $freeKm,
            'base_rental_amount' => round($baseAmount, 2),
            'driver_allowance_amount' => round($driverAllowance, 2),
            'subtotal' => round($subtotal, 2),
            'discount_amount' => $discountAmount,
            'taxable_amount' => round($taxableAmount, 2),
            'gst_rate_percent' => $gstPercent,
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'igst_amount' => $igst,
            'total_tax' => $totalGst,
            'total_estimated_amount' => round($totalEstimated, 2),
            'security_deposit_amount' => round($securityDeposit, 2),
            'sac_code' => $rentalType->sac_code,
        ];
    }
}

