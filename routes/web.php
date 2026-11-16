<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RentalOperationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicCatalogController;

// Public Website & Vehicle Showcase
Route::get('/', [PublicCatalogController::class, 'index'])->name('public.catalog');
Route::get('/vehicle/{vehicle}', [PublicCatalogController::class, 'vehicleDetails'])->name('public.vehicle');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated ERP Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fleet Management
    Route::get('/fleet-calendar', [FleetController::class, 'calendar'])->name('fleet.calendar');
    Route::resource('fleet', FleetController::class);

    // Bookings & Reservations
    Route::resource('bookings', BookingController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/bookings/{booking}/allocate', [BookingController::class, 'allocate'])->name('bookings.allocate');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/api/check-availability', [BookingController::class, 'checkAvailability'])->name('api.availability');
    Route::post('/api/calculate-pricing', [BookingController::class, 'calculatePricing'])->name('api.pricing');

    // Operations (Handover, Return, Damage, Agreements)
    Route::get('/operations/handover/{booking}', [RentalOperationController::class, 'showHandoverForm'])->name('operations.handover');
    Route::post('/operations/handover/{booking}', [RentalOperationController::class, 'submitHandover'])->name('operations.handover.submit');
    Route::get('/operations/return/{booking}', [RentalOperationController::class, 'showReturnForm'])->name('operations.return');
    Route::post('/operations/return/{booking}', [RentalOperationController::class, 'submitReturn'])->name('operations.return.submit');
    Route::get('/operations/agreement/{booking}/pdf', [RentalOperationController::class, 'downloadAgreement'])->name('operations.agreement.pdf');
    Route::get('/operations/invoice/{invoice}/pdf', [RentalOperationController::class, 'downloadInvoice'])->name('operations.invoice.pdf');

    // Customer Management
    Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/customers/{customer}/verify-dl', [CustomerController::class, 'verifyDl'])->name('customers.verify-dl');

    // Driver Management
    Route::resource('drivers', DriverController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/drivers/{driver}/attendance', [DriverController::class, 'logAttendance'])->name('drivers.attendance');

    // Billing, Invoicing & Escrow Deposits
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('billing.invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'showInvoice'])->name('billing.invoice.show');
    Route::post('/invoices/{invoice}/payment', [BillingController::class, 'recordPayment'])->name('billing.invoice.payment');
    Route::get('/deposits', [BillingController::class, 'deposits'])->name('billing.deposits');
    Route::post('/deposits/{deposit}/refund', [BillingController::class, 'refundDeposit'])->name('billing.deposit.refund');
    Route::get('/ledger', [BillingController::class, 'ledger'])->name('billing.ledger');

    // Reports & Fleet Analytics
    Route::get('/reports/profitability', [ReportController::class, 'profitability'])->name('reports.profitability');
    Route::get('/reports/utilization', [ReportController::class, 'fleetUtilization'])->name('reports.utilization');
});
