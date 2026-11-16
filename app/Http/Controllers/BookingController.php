<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\RentalPackage;
use App\Models\PickupLocation;
use App\Models\Driver;
use App\Models\SecurityDeposit;
use App\Models\RentalAgreement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\VehicleAvailabilityService;
use App\Services\PricingEngineService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct(
        protected VehicleAvailabilityService $availabilityService,
        protected PricingEngineService $pricingService
    ) {}

    public function index(Request $request)
    {
        $status = $request->query('status');
        $rentalTypeCode = $request->query('type');
        $search = $request->query('search');

        $query = Booking::with(['customer', 'category', 'assignedVehicle.model', 'assignedDriver', 'rentalType', 'pickupLocation'])
            ->when($status, fn($q) => $q->where('booking_status', $status))
            ->when($rentalTypeCode, function ($q) use ($rentalTypeCode) {
                $q->whereHas('rentalType', fn($rq) => $rq->where('code', $rentalTypeCode));
            })
            ->when($search, function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
            });

        $bookings = $query->orderBy('id', 'desc')->paginate(15);
        $rentalTypes = RentalType::all();

        return view('bookings.index', compact('bookings', 'status', 'rentalTypeCode', 'search', 'rentalTypes'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $categories = VehicleCategory::where('is_active', true)->get();
        $rentalTypes = RentalType::where('is_active', true)->get();
        $packages = RentalPackage::where('is_active', true)->get();
        $locations = PickupLocation::where('is_active', true)->get();

        return view('bookings.create', compact('customers', 'categories', 'rentalTypes', 'packages', 'locations'));
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:vehicle_categories,id',
            'pickup_datetime' => 'required|date',
            'return_datetime' => 'required|date|after:pickup_datetime',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $availableVehicles = $this->availabilityService->getAvailableVehicles(
            $validated['category_id'],
            $validated['pickup_datetime'],
            $validated['return_datetime'],
            $validated['branch_id'] ?? null
        );

        return response()->json([
            'count' => $availableVehicles->count(),
            'vehicles' => $availableVehicles,
        ]);
    }

    public function calculatePricing(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:vehicle_categories,id',
            'rental_type_id' => 'required|exists:rental_types,id',
            'pickup_datetime' => 'required|date',
            'return_datetime' => 'required|date|after:pickup_datetime',
            'package_id' => 'nullable|exists:rental_packages,id',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $estimate = $this->pricingService->calculateRentalEstimate(
            $validated['category_id'],
            $validated['rental_type_id'],
            $validated['pickup_datetime'],
            $validated['return_datetime'],
            $validated['package_id'] ?? null,
            (float) ($validated['discount_percent'] ?? 0)
        );

        return response()->json($estimate);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'rental_type_id' => 'required|exists:rental_types,id',
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
            'pickup_location_id' => 'required|exists:pickup_locations,id',
            'return_location_id' => 'required|exists:pickup_locations,id',
            'pickup_datetime' => 'required|date',
            'return_datetime' => 'required|date|after:pickup_datetime',
            'package_id' => 'nullable|exists:rental_packages,id',
            'assigned_vehicle_id' => 'nullable|exists:vehicles,id',
            'assigned_driver_id' => 'nullable|exists:drivers,id',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $companyId = auth()->user()->company_id ?? 1;
            $branchId = auth()->user()->branch_id ?? 1;

            // Compute official pricing snapshot
            $pricing = $this->pricingService->calculateRentalEstimate(
                $validated['vehicle_category_id'],
                $validated['rental_type_id'],
                $validated['pickup_datetime'],
                $validated['return_datetime'],
                $validated['package_id'] ?? null
            );

            // Generate unique Booking number (e.g. BK-2026-0001)
            $bookingNumber = 'BK-' . date('Y') . '-' . strtoupper(Str::random(5));

            $booking = Booking::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'booking_number' => $bookingNumber,
                'customer_id' => $validated['customer_id'],
                'rental_type_id' => $validated['rental_type_id'],
                'vehicle_category_id' => $validated['vehicle_category_id'],
                'pickup_location_id' => $validated['pickup_location_id'],
                'return_location_id' => $validated['return_location_id'],
                'pickup_datetime' => $validated['pickup_datetime'],
                'return_datetime' => $validated['return_datetime'],
                'package_id' => $validated['package_id'] ?? null,
                'assigned_vehicle_id' => $validated['assigned_vehicle_id'] ?? null,
                'assigned_driver_id' => $validated['assigned_driver_id'] ?? null,
                'booking_status' => !empty($validated['assigned_vehicle_id']) ? 'allocated' : 'confirmed',
                'booking_source' => 'direct_walkin',
                'base_rental_amount' => $pricing['base_rental_amount'],
                'driver_allowance_amount' => $pricing['driver_allowance_amount'],
                'discount_amount' => $pricing['discount_amount'],
                'tax_amount' => $pricing['total_tax'],
                'total_estimated_amount' => $pricing['total_estimated_amount'],
                'security_deposit_amount' => $pricing['security_deposit_amount'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // If Self-Drive, automatically initialize Escrow Security Deposit record
            $rentalType = RentalType::find($validated['rental_type_id']);
            if ($rentalType && !$rentalType->is_chauffeur_included && $pricing['security_deposit_amount'] > 0) {
                SecurityDeposit::create([
                    'company_id' => $companyId,
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    'deposit_number' => 'DEP-' . date('Y') . '-' . strtoupper(Str::random(5)),
                    'deposit_amount' => $pricing['security_deposit_amount'],
                    'payment_method' => 'upi',
                    'status' => 'held',
                    'received_by' => auth()->id(),
                    'notes' => 'Escrow deposit collected for self-drive booking ' . $bookingNumber,
                ]);
            }

            // Create initial rental agreement draft
            RentalAgreement::create([
                'booking_id' => $booking->id,
                'agreement_number' => 'AGR-' . date('Y') . '-' . strtoupper(Str::random(5)),
                'terms_version' => '1.0',
                'signed_by_customer_name' => $booking->customer->name,
                'signed_at' => now(),
                'status' => 'active',
            ]);

            return redirect()->route('bookings.show', $booking->id)->with('success', "Booking {$booking->booking_number} created successfully.");
        });
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'customer.documents',
            'category',
            'assignedVehicle.model.brand',
            'assignedDriver',
            'rentalType',
            'pickupLocation',
            'returnLocation',
            'agreement',
            'handover',
            'vehicleReturn',
            'deposit',
            'invoices.items',
            'settlement',
            'damageReports'
        ]);

        $availableVehicles = $this->availabilityService->getAvailableVehicles(
            $booking->vehicle_category_id,
            $booking->pickup_datetime,
            $booking->return_datetime
        );

        $availableDrivers = Driver::where('status', 'available')->get();

        return view('bookings.show', compact('booking', 'availableVehicles', 'availableDrivers'));
    }

    public function allocate(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        // Validate vehicle availability
        $isAvailable = $this->availabilityService->isVehicleAvailable(
            $validated['vehicle_id'],
            $booking->pickup_datetime,
            $booking->return_datetime,
            2,
            $booking->id
        );

        if (!$isAvailable) {
            return back()->withErrors(['vehicle_id' => 'Selected vehicle is not available for this time window (conflicting booking or scheduled maintenance).']);
        }

        $booking->update([
            'assigned_vehicle_id' => $validated['vehicle_id'],
            'assigned_driver_id' => $validated['driver_id'] ?? $booking->assigned_driver_id,
            'booking_status' => 'allocated',
        ]);

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Vehicle allocated successfully.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $booking->update([
            'booking_status' => 'cancelled',
            'notes' => ($booking->notes ? $booking->notes . "\n" : '') . "Cancelled: " . $validated['reason'],
        ]);

        // Release vehicle if allocated
        if ($booking->assignedVehicle && $booking->assignedVehicle->status === 'reserved') {
            $booking->assignedVehicle->update(['status' => 'available']);
        }

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking cancelled successfully.');
    }
}

