<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\SecurityDeposit;
use App\Models\VehicleCategory;
use App\Models\VehicleMaintenance;
use App\Models\VehicleDocument;
use App\Models\DriverAttendance;
use App\Models\DriverAllowance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Driver Portal Dashboard
        if ($user->isDriver()) {
            return $this->driverDashboard($user);
        }

        // 2. Customer Portal Dashboard
        if ($user->isCustomer()) {
            return $this->customerDashboard($user);
        }

        // 3. Fleet Manager Operations Dashboard
        if ($user->isFleetManager()) {
            return $this->fleetManagerDashboard($user);
        }

        // 4. Counter Staff Dispatch Dashboard
        if ($user->isCounterStaff()) {
            return $this->counterStaffDashboard($user);
        }

        // 5. Super Admin / Executive Owner Dashboard
        return $this->adminDashboard($user);
    }

    protected function adminDashboard($user)
    {
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $rentedVehicles = Vehicle::where('status', 'rented')->count();
        $maintenanceVehicles = Vehicle::whereIn('status', ['maintenance', 'in_repair'])->count();
        $fleetUtilization = $totalVehicles > 0 ? round(($rentedVehicles / $totalVehicles) * 100, 1) : 0;

        $totalCustomers = Customer::count();
        $totalDrivers = Driver::count();
        $activeBookings = Booking::whereIn('booking_status', ['confirmed', 'allocated', 'active'])->count();
        $todaysBookings = Booking::whereDate('pickup_datetime', Carbon::today())->count();

        // Financial KPIs
        $totalRevenue = Invoice::sum('paid_amount');
        $pendingReceivables = Invoice::sum('balance_due');
        $activeDepositsHeld = SecurityDeposit::where('status', 'held')->sum('deposit_amount');

        // Recent Bookings
        $recentBookings = Booking::with(['customer', 'category', 'assignedVehicle', 'assignedDriver', 'rentalType'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Recent Vehicles
        $recentVehicles = Vehicle::with(['model.brand', 'category', 'branch'])
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // Vehicle Categories with count
        $categoryCounts = Vehicle::selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('dashboard.index', compact(
            'totalVehicles',
            'availableVehicles',
            'rentedVehicles',
            'maintenanceVehicles',
            'fleetUtilization',
            'totalCustomers',
            'totalDrivers',
            'activeBookings',
            'todaysBookings',
            'totalRevenue',
            'pendingReceivables',
            'activeDepositsHeld',
            'recentBookings',
            'recentVehicles',
            'categoryCounts'
        ));
    }

    protected function fleetManagerDashboard($user)
    {
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $rentedVehicles = Vehicle::where('status', 'rented')->count();
        $maintenanceVehicles = Vehicle::whereIn('status', ['maintenance', 'in_repair'])->count();
        $fleetUtilization = $totalVehicles > 0 ? round(($rentedVehicles / $totalVehicles) * 100, 1) : 0;

        // Compliance & Expirations (Next 30 days)
        $expiringInsurance = Vehicle::whereNotNull('insurance_expiry_date')
            ->whereBetween('insurance_expiry_date', [now(), now()->addDays(30)])
            ->with(['model.brand', 'category'])
            ->get();

        $activeMaintenance = VehicleMaintenance::with('vehicle.model')
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_date', 'asc')
            ->get();

        $vehicles = Vehicle::with(['model.brand', 'category', 'branch'])
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $categoryCounts = Vehicle::selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('dashboard.fleet', compact(
            'totalVehicles',
            'availableVehicles',
            'rentedVehicles',
            'maintenanceVehicles',
            'fleetUtilization',
            'expiringInsurance',
            'activeMaintenance',
            'vehicles',
            'categoryCounts'
        ));
    }

    protected function counterStaffDashboard($user)
    {
        $today = Carbon::today();

        // Today's Checkouts / Handovers
        $todayPickups = Booking::with(['customer', 'category', 'assignedVehicle.model'])
            ->whereIn('booking_status', ['confirmed', 'allocated'])
            ->whereDate('pickup_datetime', $today)
            ->get();

        // Today's Scheduled Returns
        $todayReturns = Booking::with(['customer', 'category', 'assignedVehicle.model', 'handover'])
            ->where('booking_status', 'active')
            ->whereDate('return_datetime', $today)
            ->get();

        // Overdue Returns
        $overdueReturns = Booking::with(['customer', 'category', 'assignedVehicle.model'])
            ->where('booking_status', 'active')
            ->where('return_datetime', '<', now())
            ->get();

        // Unallocated Bookings
        $pendingAllocations = Booking::with(['customer', 'category'])
            ->where('booking_status', 'confirmed')
            ->whereNull('assigned_vehicle_id')
            ->get();

        return view('dashboard.counter', compact(
            'todayPickups',
            'todayReturns',
            'overdueReturns',
            'pendingAllocations'
        ));
    }

    protected function driverDashboard($user)
    {
        $driver = $user->driver ?? Driver::where('phone', $user->phone)->first();

        if (!$driver) {
            $driver = Driver::first(); // Fallback for demonstration
        }

        // Active Trip
        $activeTrip = Booking::with(['customer', 'category', 'assignedVehicle.model', 'pickupLocation', 'returnLocation', 'tripRecord'])
            ->where('assigned_driver_id', $driver?->id)
            ->where('booking_status', 'active')
            ->first();

        // Upcoming Assigned Trips
        $upcomingTrips = Booking::with(['customer', 'category', 'assignedVehicle.model', 'pickupLocation'])
            ->where('assigned_driver_id', $driver?->id)
            ->whereIn('booking_status', ['confirmed', 'allocated'])
            ->orderBy('pickup_datetime', 'asc')
            ->get();

        // Completed Trips
        $completedTrips = Booking::with(['customer', 'category', 'assignedVehicle.model'])
            ->where('assigned_driver_id', $driver?->id)
            ->where('booking_status', 'settled')
            ->orderBy('actual_return_datetime', 'desc')
            ->take(5)
            ->get();

        // Attendance today
        $attendanceToday = DriverAttendance::where('driver_id', $driver?->id)
            ->whereDate('attendance_date', Carbon::today())
            ->first();

        // Total Allowances
        $allowances = DriverAllowance::where('driver_id', $driver?->id)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $totalAllowancesEarned = DriverAllowance::where('driver_id', $driver?->id)->sum('amount');

        return view('dashboard.driver', compact(
            'driver',
            'activeTrip',
            'upcomingTrips',
            'completedTrips',
            'attendanceToday',
            'allowances',
            'totalAllowancesEarned'
        ));
    }

    protected function customerDashboard($user)
    {
        $customer = $user->customer ?? Customer::where('email', $user->email)->orWhere('phone', $user->phone)->first();

        if (!$customer) {
            $customer = Customer::first(); // Fallback for demonstration
        }

        // Active Booking
        $activeBooking = Booking::with(['category', 'assignedVehicle.model', 'pickupLocation', 'agreement'])
            ->where('customer_id', $customer?->id)
            ->where('booking_status', 'active')
            ->first();

        // Upcoming Reservations
        $upcomingBookings = Booking::with(['category', 'assignedVehicle.model', 'pickupLocation'])
            ->where('customer_id', $customer?->id)
            ->whereIn('booking_status', ['confirmed', 'allocated'])
            ->orderBy('pickup_datetime', 'asc')
            ->get();

        // Past Trips
        $pastBookings = Booking::with(['category', 'assignedVehicle.model', 'invoices'])
            ->where('customer_id', $customer?->id)
            ->whereIn('booking_status', ['settled', 'cancelled', 'completed'])
            ->orderBy('id', 'desc')
            ->get();

        // Invoices
        $invoices = Invoice::where('customer_id', $customer?->id)->orderBy('id', 'desc')->get();
        $totalSpent = Invoice::where('customer_id', $customer?->id)->sum('paid_amount');

        return view('dashboard.customer', compact(
            'customer',
            'activeBooking',
            'upcomingBookings',
            'pastBookings',
            'invoices',
            'totalSpent'
        ));
    }
}
