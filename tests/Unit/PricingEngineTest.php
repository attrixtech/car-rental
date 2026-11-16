<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Services\PricingEngineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PricingEngineTest extends TestCase
{
    use DatabaseTransactions;

    protected PricingEngineService $pricingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingService = new PricingEngineService();
    }

    public function test_pricing_calculation_for_daily_self_drive_with_gst(): void
    {
        $category = VehicleCategory::where('slug', 'hatchback')->first();
        $selfDriveType = RentalType::where('code', 'self_drive')->first();

        $base = Carbon::now()->startOfHour();
        $start = (clone $base)->addDay();
        $end = (clone $base)->addDays(3); // exactly 48 hours = 2 days

        $estimate = $this->pricingService->calculateRentalEstimate(
            $category->id,
            $selfDriveType->id,
            $start,
            $end
        );

        $this->assertEquals(2, $estimate['total_days']);
        $this->assertEquals(500, $estimate['free_km']); // 250 km * 2 days
        $this->assertEquals(3600.00, $estimate['base_rental_amount']); // 1800 * 2
        $this->assertEquals(18.00, $estimate['gst_rate_percent']);
        $this->assertEquals(648.00, $estimate['total_tax']); // 18% of 3600
        $this->assertEquals(324.00, $estimate['cgst_amount']);
        $this->assertEquals(324.00, $estimate['sgst_amount']);
        $this->assertEquals(4248.00, $estimate['total_estimated_amount']);
        $this->assertEquals(4000.00, $estimate['security_deposit_amount']);
    }

    public function test_interstate_gst_assigns_igst(): void
    {
        $category = VehicleCategory::first();
        $selfDriveType = RentalType::where('code', 'self_drive')->first();

        $start = Carbon::now()->addDay();
        $end = Carbon::now()->addDays(2); // 1 day

        $estimate = $this->pricingService->calculateRentalEstimate(
            $category->id,
            $selfDriveType->id,
            $start,
            $end,
            null,
            0.0,
            true // isInterstate = true
        );

        $this->assertEquals(0.00, $estimate['cgst_amount']);
        $this->assertEquals(0.00, $estimate['sgst_amount']);
        $this->assertGreaterThan(0.00, $estimate['igst_amount']);
        $this->assertEquals($estimate['total_tax'], $estimate['igst_amount']);
    }
}
