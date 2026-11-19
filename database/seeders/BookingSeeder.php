<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Branch;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\RentalType;
use App\Models\PickupLocation;
use App\Models\Booking;
use App\Models\RentalAgreement;
use App\Models\VehicleHandover;
use App\Models\VehicleReturn;
use App\Models\SecurityDeposit;
use App\Models\RentalSettlement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        if (!$company) {
            return;
        }

        $branchT2 = Branch::where('code', 'AGR-HQ')->first() ?? Branch::first();
        $branchBandra = Branch::where('code', 'BOM-BAN')->first() ?? Branch::first();
        $branchPune = Branch::where('code', 'PNQ-HIN')->first() ?? Branch::first();

        $adminUser = User::where('role', 'super_admin')->first() ?? User::first();
        $staffUser = User::where('role', 'counter_staff')->first() ?? $adminUser;

        $locT2 = PickupLocation::where('location_type', 'airport')->first() ?? PickupLocation::first();
        $locBandra = PickupLocation::where('name', 'like', '%Bandra%')->first() ?? PickupLocation::first();
        $locPune = PickupLocation::where('name', 'like', '%Pune%')->first() ?? PickupLocation::first();
        $locations = [$locT2, $locBandra, $locPune];

        $catHatchback = VehicleCategory::where('slug', 'hatchback')->first();
        $catSedan = VehicleCategory::where('slug', 'compact-sedan')->first();
        $catCompactSUV = VehicleCategory::where('slug', 'compact-suv')->first();
        $catMUV = VehicleCategory::where('slug', 'premium-muv')->first();
        $catLuxury = VehicleCategory::where('slug', 'luxury-sedan')->first();

        $rtSelfDrive = RentalType::where('code', 'self_drive')->first();
        $rtChauffeur = RentalType::where('code', 'chauffeur_driven')->first();
        $rtOutstation = RentalType::where('code', 'outstation')->first();
        $rtAirport = RentalType::where('code', 'airport_transfer')->first();

        // 1. Create Extra Indian Customers
        $customersData = [
            [
                'name' => 'Rahul Varma',
                'email' => 'rahul.varma@tcs.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1988-08-21',
                'address' => 'A-402, Raheja Vistas, Chandivali, Powai',
                'city' => 'Mumbai',
                'postal_code' => '400072',
                'aadhaar' => '4829-1920-5819',
                'dl' => 'MH03 20100034918',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Neha Kulkarni',
                'email' => 'neha.kulkarni@infosys.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1993-11-04',
                'address' => 'B-12, Blue Ridge Township, Hinjewadi Phase 1',
                'city' => 'Pune',
                'postal_code' => '411057',
                'aadhaar' => '3920-4819-2011',
                'dl' => 'MH12 20150048192',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Rajesh S. Iyer',
                'email' => 'rajesh.iyer@tatamotors.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1982-04-12',
                'address' => '14, Matunga East, Near Ruia College',
                'city' => 'Mumbai',
                'postal_code' => '400019',
                'aadhaar' => '7192-3849-1029',
                'dl' => 'MH01 20050019284',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Ananya Sengupta',
                'email' => 'ananya.sengupta@gmail.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1995-02-18',
                'address' => 'Tower 3, Lodha Park, Lower Parel',
                'city' => 'Mumbai',
                'postal_code' => '400013',
                'aadhaar' => '8491-2019-3841',
                'dl' => 'WB01 20170094821',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Rohan Malhotra',
                'email' => 'rohan.malhotra@ril.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1986-09-30',
                'address' => 'Floor 18, Sea Face Chambers, Worli',
                'city' => 'Mumbai',
                'postal_code' => '400018',
                'aadhaar' => '9012-3849-1024',
                'dl' => 'DL04 20080039182',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Priya Sundaram',
                'email' => 'priya.sundaram@accenture.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1991-07-25',
                'address' => 'Villa 4, Hiranandani Estate, Ghodbunder Road',
                'city' => 'Thane',
                'postal_code' => '400607',
                'aadhaar' => '2849-1029-4819',
                'dl' => 'KA03 20130094812',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Amitav Banerjee',
                'email' => 'amitav.b@hdfcbank.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1984-12-15',
                'address' => 'C-601, Kalpataru Riverside, Panvel',
                'city' => 'Navi Mumbai',
                'postal_code' => '410206',
                'aadhaar' => '6719-2019-4810',
                'dl' => 'MH46 20090029481',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Suresh R. Patel',
                'email' => 'suresh.patel@patelchem.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1979-05-10',
                'address' => '201, Diamond Plaza, Opera House',
                'city' => 'Mumbai',
                'postal_code' => '400004',
                'aadhaar' => '5829-1029-3849',
                'dl' => 'GJ01 20020084912',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Deepa Krishnan',
                'email' => 'deepa.k@wipro.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1994-03-29',
                'address' => 'Flat 503, Magarpatta City, Hadapsar',
                'city' => 'Pune',
                'postal_code' => '411028',
                'aadhaar' => '1920-3849-5819',
                'dl' => 'TN07 20160039184',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Harpreet Singh Dhillon',
                'email' => 'harpreet.dhillon@gmail.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1987-10-14',
                'address' => 'B-804, Oberoi Springs, Andheri West',
                'city' => 'Mumbai',
                'postal_code' => '400053',
                'aadhaar' => '9481-2019-3819',
                'dl' => 'PB65 20110048192',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Gaurav Chauhan',
                'email' => 'gaurav.chauhan@zomato.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1992-06-19',
                'address' => 'Flat 12, Koregaon Park Plaza, Lane 7',
                'city' => 'Pune',
                'postal_code' => '411001',
                'aadhaar' => '8491-3819-2049',
                'dl' => 'MH12 20140084912',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Tanvi Mathur',
                'email' => 'tanvi.mathur@nykaa.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1996-01-22',
                'address' => 'Sea Breeze Apts, Bandra Bandstand',
                'city' => 'Mumbai',
                'postal_code' => '400050',
                'aadhaar' => '4819-2049-1829',
                'dl' => 'RJ14 20180029481',
                'customer_type' => 'individual',
            ],
            [
                'name' => 'Farhan Akhtar',
                'email' => 'farhan.akhtar@excelent.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1985-03-08',
                'address' => 'Hill View Bungalow, Pali Hill, Bandra West',
                'city' => 'Mumbai',
                'postal_code' => '400050',
                'aadhaar' => '7192-4819-2049',
                'dl' => 'MH02 20070094819',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Meera Nambiar',
                'email' => 'meera.nambiar@deloitte.com',
                'phone' => 'xxxxx',
                'gender' => 'Female',
                'dob' => '1990-09-11',
                'address' => 'A-1002, Rustomjee Elements, Upper Juhu',
                'city' => 'Mumbai',
                'postal_code' => '400053',
                'aadhaar' => '3920-1928-4819',
                'dl' => 'KL07 20120039182',
                'customer_type' => 'corporate',
            ],
            [
                'name' => 'Siddharth Rao',
                'email' => 'siddharth.rao@microsoft.com',
                'phone' => 'xxxxx',
                'gender' => 'Male',
                'dob' => '1989-12-03',
                'address' => 'Floor 15, Vasant Oasis, Marol, Andheri East',
                'city' => 'Mumbai',
                'postal_code' => '400059',
                'aadhaar' => '8491-1029-3819',
                'dl' => 'TS09 20130084912',
                'customer_type' => 'corporate',
            ],
        ];

        $customers = [];
        // Add existing customer
        $existingCust = Customer::first();
        if ($existingCust) {
            $customers[] = $existingCust;
        }

        foreach ($customersData as $index => $cData) {
            $cust = Customer::firstOrCreate(
                ['email' => $cData['email']],
                [
                    'company_id' => $company->id,
                    'customer_code' => 'CUST-' . str_pad($index + 2, 4, '0', STR_PAD_LEFT),
                    'customer_type' => $cData['customer_type'],
                    'name' => $cData['name'],
                    'phone' => $cData['phone'],
                    'gender' => $cData['gender'],
                    'date_of_birth' => $cData['dob'],
                    'address' => $cData['address'],
                    'city' => $cData['city'],
                    'state' => 'Maharashtra',
                    'postal_code' => $cData['postal_code'],
                    'country' => 'India',
                    'id_proof_type' => 'aadhaar',
                    'id_proof_number' => $cData['aadhaar'],
                    'driving_license_number' => $cData['dl'],
                    'driving_license_expiry' => Carbon::now()->addYears(rand(2, 6)),
                    'is_dl_verified' => true,
                    'status' => 'active',
                    'loyalty_points' => rand(100, 1200),
                ]
            );

            CustomerDocument::firstOrCreate(
                ['customer_id' => $cust->id, 'document_type' => 'driving_license_front'],
                [
                    'document_number' => $cData['dl'],
                    'expiry_date' => Carbon::now()->addYears(4),
                    'file_path' => 'documents/dl_' . Str::slug($cData['name']) . '.png',
                    'verification_status' => 'verified',
                    'verified_by' => $adminUser->id,
                    'verified_at' => Carbon::now()->subMonths(rand(1, 12)),
                ]
            );

            $customers[] = $cust;
        }

        // 2. Create Extra Drivers
        $driversData = [
            [
                'code' => 'DRV-102',
                'name' => 'Santosh G. More',
                'phone' => 'xxxxx',
                'license' => 'MH02 20100084912',
                'badge' => 'BOM/TR/84912',
                'city' => 'Mumbai',
                'rating' => 4.92,
                'trips' => 184,
            ],
            [
                'code' => 'DRV-103',
                'name' => 'Ganesh S. Shinde',
                'phone' => 'xxxxx',
                'license' => 'MH12 20130094812',
                'badge' => 'PNQ/TR/39182',
                'city' => 'Pune',
                'rating' => 4.88,
                'trips' => 152,
            ],
            [
                'code' => 'DRV-104',
                'name' => 'Abdul Karim Khan',
                'phone' => 'xxxxx',
                'license' => 'MH03 20080039182',
                'badge' => 'BOM/TR/19284',
                'city' => 'Mumbai',
                'rating' => 4.97,
                'trips' => 220,
            ],
            [
                'code' => 'DRV-105',
                'name' => 'Sunil B. Jadhav',
                'phone' => 'xxxxx',
                'license' => 'MH04 20140028491',
                'badge' => 'THN/TR/58192',
                'city' => 'Thane',
                'rating' => 4.85,
                'trips' => 110,
            ],
            [
                'code' => 'DRV-106',
                'name' => 'Mahendra Yadav',
                'phone' => 'xxxxx',
                'license' => 'MH01 20110048192',
                'badge' => 'BOM/TR/48192',
                'city' => 'Mumbai',
                'rating' => 4.90,
                'trips' => 165,
            ],
        ];

        $drivers = Driver::all()->all();
        foreach ($driversData as $dData) {
            $driver = Driver::firstOrCreate(
                ['driver_code' => $dData['code']],
                [
                    'company_id' => $company->id,
                    'branch_id' => $dData['city'] === 'Pune' ? $branchPune->id : $branchT2->id,
                    'name' => $dData['name'],
                    'phone' => $dData['phone'],
                    'license_number' => $dData['license'],
                    'license_type' => 'transport',
                    'license_expiry_date' => Carbon::now()->addYears(3),
                    'badge_number' => $dData['badge'],
                    'badge_expiry_date' => Carbon::now()->addYears(2),
                    'police_verification_status' => 'verified',
                    'daily_bata_rate' => 350.00,
                    'night_bata_rate' => 350.00,
                    'overtime_hourly_rate' => 70.00,
                    'status' => 'available',
                    'rating' => $dData['rating'],
                    'total_trips' => $dData['trips'],
                    'city' => $dData['city'],
                ]
            );
            $drivers[] = $driver;
        }

        // Vehicles reference
        $vehicles = Vehicle::all()->keyBy('id');
        // Vehicle 1: Swift ZXi (Hatchback) - DO NOT assign near-future dates to keep availability test clean
        // Vehicle 2: Dzire VXi (Sedan)
        // Vehicle 3: Scorpio-N Z8L (Compact SUV)
        // Vehicle 4: Innova Crysta (MUV)
        // Vehicle 5: BMW 330Li (Luxury)
        // Vehicle 6: Innova Hycross (MUV)

        $tripNotes = [
            'Lonavala Weekend Getaway with family & scenic ghats',
            'Corporate executives airport transit to BKC Grand Hyatt',
            'Pune IT Park Client Meeting & business conference',
            'Alibaug Coastal Roadtrip via Atal Setu MTHL',
            'Shirdi Sai Baba Temple 3-day pilgrimage outstation round trip',
            'Wedding entourage luxury chauffeur service at Taj Lands End',
            'CSMIA T2 Arrival pickup - Indigo Flight 6E-204',
            'Mahabaleshwar & Panchgani hill station weekend holiday',
            'VIP delegation airport transfer - Air India Flight AI-645',
            'Film crew production equipment transit to Film City Goregaon',
            'Navi Mumbai MIDC industrial site client audit trip',
            'Dadar to Pune Koregaon Park executive outstation travel',
            'CSMIA T2 International Terminal drop - Emirates EK-501',
            'South Mumbai Heritage architectural tour & client dinner',
            'Lavasa Lake City corporate offsite trip',
            'Igatpuri & Nashik Wine Valley 2-day self-drive tour',
            'Goa coastal expressway monsoon drive',
            'CSMIA T2 Arrival pickup - Akasa Air QP-1302',
            'Bandra Kurla Complex (BKC) to Pune Shivaji Nagar round trip',
            'Corporate VIP pickup from JW Marriott Juhu to CSMIA T2',
        ];

        $flightNumbers = ['6E-204', '6E-512', 'AI-645', 'AI-802', 'UK-992', 'UK-855', 'QP-1302', 'SG-291'];

        // Define a comprehensive list of 60 realistic bookings
        // 1. SETTLED (25 bookings - past completed trips with complete invoices & deposit refunds)
        for ($i = 1; $i <= 25; $i++) {
            $daysAgo = rand(5, 55);
            $durationDays = rand(1, 5);
            $pickup = Carbon::now()->subDays($daysAgo)->setTime(rand(7, 14), 0);
            $return = (clone $pickup)->addDays($durationDays)->setTime(rand(16, 21), 0);

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = match ($i % 4) {
                0 => $rtSelfDrive,
                1 => $rtChauffeur,
                2 => $rtOutstation,
                default => $rtAirport,
            };

            $category = match ($i % 5) {
                0 => $catHatchback,
                1 => $catSedan,
                2 => $catCompactSUV,
                3 => $catMUV,
                default => $catLuxury,
            };

            // Assign vehicle (safe because past completed trips do not conflict)
            $vehicle = $vehicles->where('category_id', $category->id)->first() ?? $vehicles->first();
            $driver = $rentalType->is_chauffeur_included ? $drivers[array_rand($drivers)] : null;

            $baseAmount = (float) $category->base_daily_rate * $durationDays;
            $driverAllowance = $rentalType->is_chauffeur_included ? (350 * $durationDays) : 0;
            $taxRate = $rentalType->gst_rate_percent;
            $taxAmount = round(($baseAmount + $driverAllowance) * ($taxRate / 100), 2);
            $totalAmount = $baseAmount + $driverAllowance + $taxAmount;
            $depositAmount = $rentalType->is_chauffeur_included ? 0 : $category->security_deposit;

            $bookingNum = 'BK-2026-S' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $b = Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => $vehicle?->id,
                    'assigned_driver_id' => $driver?->id,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'actual_pickup_datetime' => $pickup,
                    'actual_return_datetime' => $return,
                    'booking_status' => 'settled',
                    'booking_source' => ['website', 'direct_walkin', 'corporate', 'phone'][rand(0, 3)],
                    'flight_number' => $rentalType->code === 'airport_transfer' ? $flightNumbers[array_rand($flightNumbers)] : null,
                    'destination_notes' => $tripNotes[array_rand($tripNotes)],
                    'base_rental_amount' => $baseAmount,
                    'driver_allowance_amount' => $driverAllowance,
                    'discount_amount' => 0.00,
                    'tax_amount' => $taxAmount,
                    'total_estimated_amount' => $totalAmount,
                    'security_deposit_amount' => $depositAmount,
                    'notes' => 'Trip completed and fully settled without dispute.',
                    'created_by' => $adminUser->id,
                ]
            );

            // Rental Agreement
            RentalAgreement::firstOrCreate(
                ['booking_id' => $b->id],
                [
                    'agreement_number' => 'AGR-2026-S' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'terms_version' => '1.0',
                    'signed_by_customer_name' => $customer->name,
                    'signed_at' => $pickup,
                    'status' => 'closed',
                ]
            );

            // Vehicle Handover
            if ($vehicle) {
                VehicleHandover::firstOrCreate(
                    ['booking_id' => $b->id],
                    [
                        'vehicle_id' => $vehicle->id,
                        'officer_id' => $staffUser->id,
                        'customer_id' => $customer->id,
                        'handover_datetime' => $pickup,
                        'odometer_start' => max(1000, $vehicle->current_odometer - ($daysAgo * 120)),
                        'fuel_level_start' => 8.00,
                        'cleanliness_interior' => true,
                        'cleanliness_exterior' => true,
                        'spare_tyre_present' => true,
                        'jack_tools_present' => true,
                        'documents_in_car' => true,
                        'notes' => 'Pristine vehicle condition verified on checkout.',
                    ]
                );

                // Vehicle Return
                VehicleReturn::firstOrCreate(
                    ['booking_id' => $b->id],
                    [
                        'vehicle_id' => $vehicle->id,
                        'receiving_officer_id' => $staffUser->id,
                        'customer_id' => $customer->id,
                        'return_datetime' => $return,
                        'odometer_end' => max(1000, $vehicle->current_odometer - ($daysAgo * 120) + ($durationDays * 180)),
                        'fuel_level_end' => 8.00,
                        'excess_km' => 0,
                        'late_hours' => 0,
                        'cleaning_required' => false,
                        'cleaning_charge' => 0.00,
                        'fuel_shortfall_litres' => 0.00,
                        'fuel_shortfall_charge' => 0.00,
                        'notes' => 'Returned on time in clean condition.',
                    ]
                );
            }

            // Settlement
            RentalSettlement::firstOrCreate(
                ['booking_id' => $b->id],
                [
                    'base_rental_charged' => $baseAmount,
                    'excess_km_charged' => 0.00,
                    'late_hours_charged' => 0.00,
                    'fuel_deficit_charged' => 0.00,
                    'damage_charges' => 0.00,
                    'toll_parking_charges' => 0.00,
                    'discount_applied' => 0.00,
                    'tax_charged' => $taxAmount,
                    'gross_final_amount' => $totalAmount,
                    'advance_paid' => $totalAmount,
                    'deposit_held' => $depositAmount,
                    'deposit_deductions' => 0.00,
                    'deposit_refund_amount' => $depositAmount,
                    'balance_payable_by_customer' => 0.00,
                    'settled_by' => $adminUser->id,
                    'settled_at' => $return,
                ]
            );

            // Escrow Deposit if Self-Drive
            if ($depositAmount > 0) {
                SecurityDeposit::firstOrCreate(
                    ['booking_id' => $b->id],
                    [
                        'company_id' => $company->id,
                        'customer_id' => $customer->id,
                        'deposit_number' => 'DEP-2026-S' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'deposit_amount' => $depositAmount,
                        'payment_method' => 'upi',
                        'transaction_reference' => 'UPI/' . rand(10000000, 99999999),
                        'status' => 'refunded',
                        'deductions_amount' => 0.00,
                        'refunded_amount' => $depositAmount,
                        'refunded_at' => $return,
                        'refund_reference' => 'REF/' . rand(10000000, 99999999),
                        'received_by' => $staffUser->id,
                        'notes' => 'Zero damage. 100% security deposit refunded via UPI.',
                    ]
                );
            }

            // Invoice
            $inv = Invoice::firstOrCreate(
                ['booking_id' => $b->id],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'invoice_number' => 'INV-2026-S' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'invoice_date' => $return->toDateString(),
                    'due_date' => $return->toDateString(),
                    'place_of_supply' => 'Maharashtra (27)',
                    'subtotal' => $baseAmount + $driverAllowance,
                    'discount_amount' => 0.00,
                    'taxable_amount' => $baseAmount + $driverAllowance,
                    'cgst_rate' => $taxRate / 2,
                    'cgst_amount' => round($taxAmount / 2, 2),
                    'sgst_rate' => $taxRate / 2,
                    'sgst_amount' => round($taxAmount / 2, 2),
                    'igst_rate' => 0.00,
                    'igst_amount' => 0.00,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'paid',
                ]
            );

            InvoiceItem::firstOrCreate(
                ['invoice_id' => $inv->id, 'item_description' => 'Car Rental Base Charge'],
                [
                    'sac_code' => $rentalType->sac_code,
                    'quantity' => $durationDays,
                    'unit_rate' => $category->base_daily_rate,
                    'total_amount' => $baseAmount,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                ]
            );
        }

        // 2. RETURNED (6 bookings - returned today/yesterday, awaiting settlement)
        for ($i = 1; $i <= 6; $i++) {
            $pickup = Carbon::now()->subDays(rand(2, 4))->setTime(9, 0);
            $return = Carbon::now()->subHours(rand(1, 10));

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = ($i % 2 === 0) ? $rtSelfDrive : $rtChauffeur;
            $category = match ($i % 4) {
                0 => $catSedan,
                1 => $catCompactSUV,
                2 => $catMUV,
                default => $catHatchback,
            };

            $vehicle = $vehicles->where('category_id', $category->id)->first() ?? $vehicles->first();
            $driver = $rentalType->is_chauffeur_included ? $drivers[array_rand($drivers)] : null;

            $baseAmount = (float) $category->base_daily_rate * 2;
            $driverAllowance = $rentalType->is_chauffeur_included ? 700 : 0;
            $taxRate = $rentalType->gst_rate_percent;
            $taxAmount = round(($baseAmount + $driverAllowance) * ($taxRate / 100), 2);
            $totalAmount = $baseAmount + $driverAllowance + $taxAmount;
            $depositAmount = $rentalType->is_chauffeur_included ? 0 : $category->security_deposit;

            $bookingNum = 'BK-2026-R' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $b = Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => $vehicle?->id,
                    'assigned_driver_id' => $driver?->id,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'actual_pickup_datetime' => $pickup,
                    'actual_return_datetime' => $return,
                    'booking_status' => 'returned',
                    'booking_source' => 'website',
                    'destination_notes' => $tripNotes[array_rand($tripNotes)],
                    'base_rental_amount' => $baseAmount,
                    'driver_allowance_amount' => $driverAllowance,
                    'discount_amount' => 0.00,
                    'tax_amount' => $taxAmount,
                    'total_estimated_amount' => $totalAmount,
                    'security_deposit_amount' => $depositAmount,
                    'notes' => 'Vehicle returned at hub, awaiting counter staff final settlement review.',
                    'created_by' => $adminUser->id,
                ]
            );

            RentalAgreement::firstOrCreate(
                ['booking_id' => $b->id],
                [
                    'agreement_number' => 'AGR-2026-R' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'terms_version' => '1.0',
                    'signed_by_customer_name' => $customer->name,
                    'signed_at' => $pickup,
                    'status' => 'active',
                ]
            );

            if ($depositAmount > 0) {
                SecurityDeposit::firstOrCreate(
                    ['booking_id' => $b->id],
                    [
                        'company_id' => $company->id,
                        'customer_id' => $customer->id,
                        'deposit_number' => 'DEP-2026-R' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'deposit_amount' => $depositAmount,
                        'payment_method' => 'card',
                        'transaction_reference' => 'TXN/' . rand(10000000, 99999999),
                        'status' => 'held',
                        'deductions_amount' => 0.00,
                        'refunded_amount' => 0.00,
                        'received_by' => $staffUser->id,
                        'notes' => 'Security deposit held pending return vehicle inspection.',
                    ]
                );
            }
        }

        // 3. ACTIVE ON-TRIP (8 bookings - currently out on road)
        // To prevent temporal conflict with tests, use vehicles 2, 3, 4, 5, 6
        $activeVehicles = $vehicles->where('id', '>', 1)->values();
        for ($i = 1; $i <= 8; $i++) {
            $pickup = Carbon::now()->subHours(rand(4, 28));
            $return = Carbon::now()->addHours(rand(12, 48));

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = match ($i % 3) {
                0 => $rtSelfDrive,
                1 => $rtChauffeur,
                default => $rtOutstation,
            };

            $category = match ($i % 4) {
                0 => $catSedan,
                1 => $catCompactSUV,
                2 => $catMUV,
                default => $catLuxury,
            };

            $vehicle = $activeVehicles[$i % count($activeVehicles)];
            $driver = $rentalType->is_chauffeur_included ? $drivers[array_rand($drivers)] : null;

            $baseAmount = (float) $category->base_daily_rate * 2;
            $driverAllowance = $rentalType->is_chauffeur_included ? 700 : 0;
            $taxRate = $rentalType->gst_rate_percent;
            $taxAmount = round(($baseAmount + $driverAllowance) * ($taxRate / 100), 2);
            $totalAmount = $baseAmount + $driverAllowance + $taxAmount;
            $depositAmount = $rentalType->is_chauffeur_included ? 0 : $category->security_deposit;

            $bookingNum = 'BK-2026-A' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $b = Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => $vehicle?->id,
                    'assigned_driver_id' => $driver?->id,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'actual_pickup_datetime' => $pickup,
                    'actual_return_datetime' => null,
                    'booking_status' => 'active',
                    'booking_source' => 'website',
                    'destination_notes' => $tripNotes[array_rand($tripNotes)],
                    'base_rental_amount' => $baseAmount,
                    'driver_allowance_amount' => $driverAllowance,
                    'discount_amount' => 0.00,
                    'tax_amount' => $taxAmount,
                    'total_estimated_amount' => $totalAmount,
                    'security_deposit_amount' => $depositAmount,
                    'notes' => 'Active rental on trip. Live GPS telemetry tracking active.',
                    'created_by' => $staffUser->id,
                ]
            );

            RentalAgreement::firstOrCreate(
                ['booking_id' => $b->id],
                [
                    'agreement_number' => 'AGR-2026-A' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'terms_version' => '1.0',
                    'signed_by_customer_name' => $customer->name,
                    'signed_at' => $pickup,
                    'status' => 'active',
                ]
            );

            if ($depositAmount > 0) {
                SecurityDeposit::firstOrCreate(
                    ['booking_id' => $b->id],
                    [
                        'company_id' => $company->id,
                        'customer_id' => $customer->id,
                        'deposit_number' => 'DEP-2026-A' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'deposit_amount' => $depositAmount,
                        'payment_method' => 'upi',
                        'transaction_reference' => 'UPI/' . rand(10000000, 99999999),
                        'status' => 'held',
                        'deductions_amount' => 0.00,
                        'refunded_amount' => 0.00,
                        'received_by' => $staffUser->id,
                        'notes' => 'Active security deposit held in escrow account 2001.',
                    ]
                );
            }
        }

        // 4. ALLOCATED / READY FOR HANDOVER (10 bookings - starting today or tomorrow)
        for ($i = 1; $i <= 10; $i++) {
            $pickup = Carbon::now()->addHours(rand(1, 18));
            $return = (clone $pickup)->addDays(rand(2, 4))->setTime(18, 0);

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = match ($i % 4) {
                0 => $rtSelfDrive,
                1 => $rtChauffeur,
                2 => $rtAirport,
                default => $rtOutstation,
            };

            $category = match ($i % 5) {
                0 => $catSedan,
                1 => $catCompactSUV,
                2 => $catMUV,
                3 => $catLuxury,
                default => $catHatchback,
            };

            // Use vehicles other than 1 to avoid unit test temporal conflicts
            $vehicle = $activeVehicles[$i % count($activeVehicles)];
            $driver = $rentalType->is_chauffeur_included ? $drivers[array_rand($drivers)] : null;

            $durationDays = 2;
            $baseAmount = (float) $category->base_daily_rate * $durationDays;
            $driverAllowance = $rentalType->is_chauffeur_included ? 700 : 0;
            $taxRate = $rentalType->gst_rate_percent;
            $taxAmount = round(($baseAmount + $driverAllowance) * ($taxRate / 100), 2);
            $totalAmount = $baseAmount + $driverAllowance + $taxAmount;
            $depositAmount = $rentalType->is_chauffeur_included ? 0 : $category->security_deposit;

            $bookingNum = 'BK-2026-H' . str_pad($i, 4, '0', STR_PAD_LEFT);

            Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => $vehicle?->id,
                    'assigned_driver_id' => $driver?->id,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'booking_status' => 'allocated',
                    'booking_source' => 'direct_walkin',
                    'flight_number' => $rentalType->code === 'airport_transfer' ? $flightNumbers[array_rand($flightNumbers)] : null,
                    'destination_notes' => $tripNotes[array_rand($tripNotes)],
                    'base_rental_amount' => $baseAmount,
                    'driver_allowance_amount' => $driverAllowance,
                    'discount_amount' => 0.00,
                    'tax_amount' => $taxAmount,
                    'total_estimated_amount' => $totalAmount,
                    'security_deposit_amount' => $depositAmount,
                    'notes' => 'Vehicle cleaned, sanitized, and parked in Dispatch Bay 3.',
                    'created_by' => $staffUser->id,
                ]
            );
        }

        // 5. CONFIRMED (12 bookings - upcoming future reservations, unassigned/assigned)
        for ($i = 1; $i <= 12; $i++) {
            $pickup = Carbon::now()->addDays(rand(3, 14))->setTime(rand(8, 16), 0);
            $return = (clone $pickup)->addDays(rand(2, 5))->setTime(18, 0);

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = match ($i % 4) {
                0 => $rtSelfDrive,
                1 => $rtChauffeur,
                2 => $rtOutstation,
                default => $rtAirport,
            };

            $category = match ($i % 5) {
                0 => $catHatchback,
                1 => $catSedan,
                2 => $catCompactSUV,
                3 => $catMUV,
                default => $catLuxury,
            };

            $durationDays = 3;
            $baseAmount = (float) $category->base_daily_rate * $durationDays;
            $driverAllowance = $rentalType->is_chauffeur_included ? (350 * $durationDays) : 0;
            $taxRate = $rentalType->gst_rate_percent;
            $taxAmount = round(($baseAmount + $driverAllowance) * ($taxRate / 100), 2);
            $totalAmount = $baseAmount + $driverAllowance + $taxAmount;
            $depositAmount = $rentalType->is_chauffeur_included ? 0 : $category->security_deposit;

            $bookingNum = 'BK-2026-C' . str_pad($i, 4, '0', STR_PAD_LEFT);

            Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => null, // Unallocated waiting in queue
                    'assigned_driver_id' => null,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'booking_status' => 'confirmed',
                    'booking_source' => 'website',
                    'flight_number' => $rentalType->code === 'airport_transfer' ? $flightNumbers[array_rand($flightNumbers)] : null,
                    'destination_notes' => $tripNotes[array_rand($tripNotes)],
                    'base_rental_amount' => $baseAmount,
                    'driver_allowance_amount' => $driverAllowance,
                    'discount_amount' => 0.00,
                    'tax_amount' => $taxAmount,
                    'total_estimated_amount' => $totalAmount,
                    'security_deposit_amount' => $depositAmount,
                    'notes' => 'Customer confirmed online reservation. Awaiting fleet vehicle allocation.',
                    'created_by' => $adminUser->id,
                ]
            );
        }

        // 6. CANCELLED (4 bookings)
        for ($i = 1; $i <= 4; $i++) {
            $pickup = Carbon::now()->subDays(rand(1, 10));
            $return = (clone $pickup)->addDays(2);

            $customer = $customers[array_rand($customers)];
            $loc = $locations[array_rand($locations)];
            $rentalType = ($i % 2 === 0) ? $rtSelfDrive : $rtChauffeur;
            $category = $catSedan;

            $bookingNum = 'BK-2026-X' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $b = Booking::firstOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'company_id' => $company->id,
                    'branch_id' => $loc->branch_id,
                    'customer_id' => $customer->id,
                    'rental_type_id' => $rentalType->id,
                    'vehicle_category_id' => $category->id,
                    'assigned_vehicle_id' => null,
                    'assigned_driver_id' => null,
                    'pickup_location_id' => $loc->id,
                    'return_location_id' => $loc->id,
                    'pickup_datetime' => $pickup,
                    'return_datetime' => $return,
                    'booking_status' => 'cancelled',
                    'booking_source' => 'phone',
                    'base_rental_amount' => 4800.00,
                    'driver_allowance_amount' => 0.00,
                    'discount_amount' => 0.00,
                    'tax_amount' => 864.00,
                    'total_estimated_amount' => 5664.00,
                    'security_deposit_amount' => 5000.00,
                    'notes' => 'Flight cancelled by airline; customer requested cancellation.',
                    'created_by' => $staffUser->id,
                ]
            );

            \Illuminate\Support\Facades\DB::table('booking_cancellations')->updateOrInsert(
                ['booking_id' => $b->id],
                [
                    'cancelled_by' => $staffUser->id,
                    'reason' => 'Customer travel schedule change due to flight cancellation.',
                    'cancellation_fee' => 0.00,
                    'refund_amount' => 5664.00,
                    'refund_status' => 'processed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
