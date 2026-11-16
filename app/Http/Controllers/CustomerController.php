<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerDocument;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $customers = Customer::withCount('bookings')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%");
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('customers.index', compact('customers', 'search', 'status'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'email' => 'nullable|email',
            'customer_type' => 'required|in:individual,corporate',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'nullable|string',
            'id_proof_type' => 'nullable|string',
            'id_proof_number' => 'nullable|string',
            'driving_license_number' => 'nullable|string',
            'driving_license_expiry' => 'nullable|date',
            'is_dl_verified' => 'nullable|boolean',
        ]);

        $validated['company_id'] = auth()->user()->company_id ?? 1;
        $validated['customer_code'] = 'CUST-' . strtoupper(Str::random(6));
        $validated['is_dl_verified'] = $request->boolean('is_dl_verified');
        $validated['status'] = 'active';

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer->id)->with('success', "Customer {$customer->name} registered successfully.");
    }

    public function show(Customer $customer)
    {
        $customer->load(['documents', 'bookings.category', 'bookings.assignedVehicle', 'invoices', 'deposits']);
        return view('customers.show', compact('customer'));
    }

    public function verifyDl(Customer $customer)
    {
        $customer->update(['is_dl_verified' => true]);
        return back()->with('success', "Driving License for {$customer->name} verified successfully.");
    }
}

