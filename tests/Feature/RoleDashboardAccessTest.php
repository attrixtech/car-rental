<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleDashboardAccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_super_admin_sees_executive_owner_dashboard(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->actingAs($admin);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Fleet & Rental Operations Dashboard');
        $response->assertSee('General Ledger');
        $response->assertSee('Revenue Settled');
    }

    public function test_fleet_manager_sees_fleet_maintenance_dashboard(): void
    {
        $fleetManager = User::where('role', 'fleet_manager')->first();
        $this->actingAs($fleetManager);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Fleet Management & Maintenance Command');
        $response->assertSee('Upcoming Insurance Expirations');
        $response->assertDontSee('General Ledger'); // Restricted from financial accounting
    }

    public function test_counter_staff_sees_dispatch_counter_dashboard(): void
    {
        $staff = User::where('role', 'counter_staff')->first();
        $this->actingAs($staff);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Rental Counter Operations');
        $response->assertSee("Scheduled Pickups");
        $response->assertDontSee('General Ledger'); // Restricted from financial accounting
    }

    public function test_driver_sees_chauffeur_duty_portal(): void
    {
        $driver = User::where('role', 'driver')->first();
        $this->actingAs($driver);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Chauffeur Duty');
        $response->assertSee('Earned Driver Batas');
        $response->assertDontSee('Fleet Registry'); // Restricted from full fleet management
        $response->assertDontSee('General Ledger');
    }

    public function test_customer_sees_customer_self_service_portal(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->actingAs($customer);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('My Customer Portal');
        $response->assertSee('My Invoices');
        $response->assertDontSee('Chauffeur Roster'); // Restricted from driver operations
        $response->assertDontSee('Fleet Registry');
        $response->assertDontSee('General Ledger');
    }
}
