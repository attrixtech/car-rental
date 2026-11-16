<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\RentalPackage;
use App\Models\PickupLocation;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $fuelType = $request->query('fuel_type');
        $transmission = $request->query('transmission');

        $categories = VehicleCategory::where('is_active', true)->get();
        $rentalTypes = RentalType::where('is_active', true)->get();
        $packages = RentalPackage::where('is_active', true)->get();
        $locations = PickupLocation::where('is_active', true)->get();

        $vehicles = Vehicle::with(['model.brand', 'category', 'branch'])
            ->where('status', 'available')
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($fuelType, fn($q) => $q->where('fuel_type', $fuelType))
            ->when($transmission, fn($q) => $q->where('transmission', $transmission))
            ->orderBy('daily_rental_price', 'asc')
            ->get();

        return view('public.catalog', compact('vehicles', 'categories', 'rentalTypes', 'packages', 'locations', 'categoryId', 'fuelType', 'transmission'));
    }

    public function vehicleDetails(Vehicle $vehicle)
    {
        $vehicle->load(['model.brand', 'category', 'branch', 'photos']);
        $locations = PickupLocation::where('is_active', true)->get();
        $rentalTypes = RentalType::where('is_active', true)->get();

        return view('public.vehicle_details', compact('vehicle', 'locations', 'rentalTypes'));
    }
}

