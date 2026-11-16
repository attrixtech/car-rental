<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverAttendance;
use App\Models\DriverAllowance;
use App\Models\Branch;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $drivers = Driver::with(['branch', 'bookings' => fn($q) => $q->where('booking_status', 'active')])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('driver_code', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('drivers.index', compact('drivers', 'status', 'search'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('drivers.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string',
            'phone' => 'required|string|unique:drivers,phone',
            'license_number' => 'required|string|unique:drivers,license_number',
            'license_type' => 'required|string',
            'license_expiry_date' => 'required|date',
            'badge_number' => 'nullable|string',
            'daily_bata_rate' => 'required|numeric|min:0',
            'night_bata_rate' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id ?? 1;
        $validated['driver_code'] = 'DRV-' . strtoupper(Str::random(5));
        $validated['status'] = 'available';

        $driver = Driver::create($validated);

        return redirect()->route('drivers.index')->with('success', "Driver {$driver->name} registered successfully.");
    }

    public function show(Driver $driver)
    {
        $driver->load(['branch', 'attendances', 'allowances', 'bookings.category', 'bookings.customer']);
        return view('drivers.show', compact('driver'));
    }

    public function logAttendance(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,on_trip,half_day,leave',
            'in_time' => 'nullable',
            'out_time' => 'nullable',
            'notes' => 'nullable|string',
        ]);

        DriverAttendance::updateOrCreate(
            ['driver_id' => $driver->id, 'attendance_date' => $validated['attendance_date']],
            $validated
        );

        return back()->with('success', 'Attendance recorded successfully.');
    }
}

