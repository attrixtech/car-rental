<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\Branch;
use App\Models\PickupLocation;
use App\Models\VehicleDocument;
use App\Models\VehicleStatusHistory;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $branchId = $request->query('branch_id');
        $search = $request->query('search');

        $query = Vehicle::with(['model.brand', 'category', 'branch', 'currentLocation'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($search, function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('vin_number', 'like', "%{$search}%")
                  ->orWhereHas('model', fn($mq) => $mq->where('name', 'like', "%{$search}%"));
            });

        $vehicles = $query->orderBy('id', 'desc')->paginate(15);
        $categories = VehicleCategory::all();
        $branches = Branch::all();

        return view('fleet.index', compact('vehicles', 'categories', 'branches', 'status', 'categoryId', 'branchId', 'search'));
    }

    public function create()
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::all();
        $models = VehicleModel::with('brand')->get();
        $branches = Branch::all();
        $locations = PickupLocation::all();

        return view('fleet.create', compact('categories', 'brands', 'models', 'branches', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|unique:vehicles,registration_number',
            'vin_number' => 'nullable|string|unique:vehicles,vin_number',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:vehicle_categories,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'color' => 'required|string',
            'manufacture_year' => 'required|integer|min:2015|max:2026',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seating_capacity' => 'required|integer|min:2|max:12',
            'current_odometer' => 'required|integer|min:0',
            'fuel_tank_capacity' => 'required|numeric|min:10',
            'daily_rental_price' => 'required|numeric|min:100',
            'hourly_rental_price' => 'nullable|numeric|min:10',
            'insurance_expiry_date' => 'nullable|date',
            'fitness_expiry_date' => 'nullable|date',
            'puc_expiry_date' => 'nullable|date',
            'features' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id ?? 1;
        $validated['status'] = 'available';
        $validated['current_fuel_level'] = 8.00;

        $vehicle = Vehicle::create($validated);

        return redirect()->route('fleet.show', $vehicle->id)->with('success', "Vehicle {$vehicle->registration_number} added successfully.");
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['model.brand', 'category', 'branch', 'currentLocation', 'documents', 'photos', 'bookings.customer', 'maintenanceRecords']);
        return view('fleet.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::all();
        $models = VehicleModel::with('brand')->get();
        $branches = Branch::all();
        $locations = PickupLocation::all();

        return view('fleet.edit', compact('vehicle', 'categories', 'brands', 'models', 'branches', 'locations'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'registration_number' => "required|string|unique:vehicles,registration_number,{$vehicle->id}",
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:vehicle_categories,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'color' => 'required|string',
            'manufacture_year' => 'required|integer',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seating_capacity' => 'required|integer',
            'daily_rental_price' => 'required|numeric|min:100',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $vehicle->status;
        $vehicle->update($validated);

        if ($oldStatus !== $validated['status']) {
            VehicleStatusHistory::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'reason' => 'Manual update via fleet management',
            ]);
        }

        return redirect()->route('fleet.show', $vehicle->id)->with('success', 'Vehicle updated successfully.');
    }

    public function calendar()
    {
        $vehicles = Vehicle::with(['model.brand', 'category', 'bookings' => function($q) {
            $q->whereIn('booking_status', ['confirmed', 'allocated', 'active']);
        }])->get();

        return view('fleet.calendar', compact('vehicles'));
    }
}

