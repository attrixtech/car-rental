<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\PickupLocation;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\Driver;
use App\Models\RentalType;
use App\Models\RentalRatePlan;
use App\Models\RentalPackage;
use App\Models\Booking;
use App\Models\SecurityDeposit;
use App\Models\ChartOfAccount;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions
        $superAdminRole = Role::create(['name' => 'super_admin', 'label' => 'Super Administrator', 'description' => 'Unrestricted ERP control']);
        $companyAdminRole = Role::create(['name' => 'company_admin', 'label' => 'Company Admin', 'description' => 'Full control over company operations']);
        $branchManagerRole = Role::create(['name' => 'branch_manager', 'label' => 'Branch Manager', 'description' => 'Manages local fleet, bookings, staff']);
        $fleetManagerRole = Role::create(['name' => 'fleet_manager', 'label' => 'Fleet & Operations Manager', 'description' => 'Controls vehicles, maintenance, compliance']);
        $counterStaffRole = Role::create(['name' => 'counter_staff', 'label' => 'Counter / Dispatch Staff', 'description' => 'Handles handovers, returns, customer intake']);
        $accountantRole = Role::create(['name' => 'accountant', 'label' => 'Finance & Accountant', 'description' => 'Manages invoicing, deposits, ledgers']);
        $driverRole = Role::create(['name' => 'driver', 'label' => 'Chauffeur / Driver', 'description' => 'Accesses assigned trips and duty sheets']);
        $customerRole = Role::create(['name' => 'customer', 'label' => 'Customer / Booker', 'description' => 'Web portal booking and rental history']);

        $modules = ['fleet', 'booking', 'operations', 'customer', 'driver', 'finance', 'maintenance', 'reports'];
        foreach ($modules as $mod) {
            foreach (['view', 'create', 'edit', 'delete'] as $act) {
                $perm = Permission::create([
                    'name' => "{$mod}.{$act}",
                    'label' => ucfirst($act) . ' ' . ucfirst($mod),
                    'module' => $mod,
                ]);
                $superAdminRole->permissions()->attach($perm);
                $companyAdminRole->permissions()->attach($perm);
            }
        }

        // 2. Company Profile
        $company = Company::create([
            'name' => 'DriveSphere Mobility Solutions Pvt. Ltd.',
            'legal_name' => 'DriveSphere Car Rentals Private Limited',
            'email' => 'operations@drivesphere.com',
            'phone' => '+91 22 6789 4400',
            'gstin' => '27AABCD1234E1Z5',
            'pan' => 'AABCD1234E',
            'cin' => 'U63030MH2021PTC359871',
            'currency' => 'INR',
            'currency_symbol' => '₹',
            'address' => 'Floor 4, Velocity Tower, Western Express Highway, Andheri East',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
            'postal_code' => '400069',
            'status' => 'active',
        ]);

        // 3. Branches
        $branchT2 = Branch::create([
            'company_id' => $company->id,
            'name' => 'Mumbai Airport Terminal 2 Hub',
            'code' => 'BOM-T2',
            'phone' => '+91 22 2682 9101',
            'email' => 'bom.t2@drivesphere.com',
            'address' => 'Level 1 Car Rental Zone, Chhatrapati Shivaji Maharaj International Airport',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400099',
            'is_airport_hub' => true,
            'is_headquarters' => true,
        ]);

        $branchBandra = Branch::create([
            'company_id' => $company->id,
            'name' => 'Mumbai Bandra West Hub',
            'code' => 'BOM-BAN',
            'phone' => '+91 22 2640 1122',
            'email' => 'bandra@drivesphere.com',
            'address' => 'Plot 42, Hill Road, Bandra West',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400050',
            'is_airport_hub' => false,
        ]);

        $branchPune = Branch::create([
            'company_id' => $company->id,
            'name' => 'Pune Hinjewadi Tech Hub',
            'code' => 'PNQ-HIN',
            'phone' => '+91 20 6712 8800',
            'email' => 'pune@drivesphere.com',
            'address' => 'Phase 1, Hinjewadi Rajiv Gandhi Infotech Park',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'postal_code' => '411057',
            'is_airport_hub' => false,
        ]);

        // 4. Users
        $adminUser = User::create([
            'company_id' => $company->id,
            'branch_id' => $branchT2->id,
            'role_id' => $superAdminRole->id,
            'name' => 'Rajesh Sharma',
            'email' => 'admin@drivesphere.com',
            'phone' => '+91 98201 12345',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $fleetUser = User::create([
            'company_id' => $company->id,
            'branch_id' => $branchT2->id,
            'role_id' => $fleetManagerRole->id,
            'name' => 'Vikram Deshmukh',
            'email' => 'fleet@drivesphere.com',
            'phone' => '+91 98202 23456',
            'password' => Hash::make('password123'),
            'role' => 'fleet_manager',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $staffUser = User::create([
            'company_id' => $company->id,
            'branch_id' => $branchBandra->id,
            'role_id' => $counterStaffRole->id,
            'name' => 'Pooja Nair',
            'email' => 'staff@drivesphere.com',
            'phone' => '+91 98203 34567',
            'password' => Hash::make('password123'),
            'role' => 'counter_staff',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // 5. Pickup & Return Locations
        $locT2 = PickupLocation::create([
            'company_id' => $company->id,
            'branch_id' => $branchT2->id,
            'name' => 'CSMIA Terminal 2 Airport Hub',
            'location_type' => 'airport',
            'address' => 'Arrival Gate 4 Parking, CSMIA T2',
            'city' => 'Mumbai',
            'convenience_fee' => 250.00,
        ]);

        $locBandra = PickupLocation::create([
            'company_id' => $company->id,
            'branch_id' => $branchBandra->id,
            'name' => 'Bandra West Branch Office',
            'location_type' => 'branch',
            'address' => 'Plot 42, Hill Road, Bandra West',
            'city' => 'Mumbai',
            'convenience_fee' => 0.00,
        ]);

        $locPune = PickupLocation::create([
            'company_id' => $company->id,
            'branch_id' => $branchPune->id,
            'name' => 'Pune Hinjewadi Depot',
            'location_type' => 'branch',
            'address' => 'Hinjewadi Phase 1 Circle',
            'city' => 'Pune',
            'convenience_fee' => 0.00,
        ]);

        // 6. Vehicle Categories
        $catHatchback = VehicleCategory::create([
            'company_id' => $company->id,
            'name' => 'Hatchback',
            'slug' => 'hatchback',
            'description' => 'Agile, fuel-efficient city cars for daily commutes and short trips.',
            'seating_capacity' => 5,
            'baggage_capacity' => 2,
            'base_hourly_rate' => 120.00,
            'base_daily_rate' => 1800.00,
            'security_deposit' => 4000.00,
            'excess_km_rate' => 11.00,
            'excess_hour_rate' => 150.00,
            'free_km_per_day' => 250,
        ]);

        $catSedan = VehicleCategory::create([
            'company_id' => $company->id,
            'name' => 'Compact Sedan',
            'slug' => 'compact-sedan',
            'description' => 'Comfortable executive sedans with generous trunk capacity.',
            'seating_capacity' => 5,
            'baggage_capacity' => 3,
            'base_hourly_rate' => 150.00,
            'base_daily_rate' => 2400.00,
            'security_deposit' => 5000.00,
            'excess_km_rate' => 13.00,
            'excess_hour_rate' => 180.00,
            'free_km_per_day' => 250,
        ]);

        $catCompactSUV = VehicleCategory::create([
            'company_id' => $company->id,
            'name' => 'Compact SUV',
            'slug' => 'compact-suv',
            'description' => 'High ground clearance and rugged utility for highway & hill driving.',
            'seating_capacity' => 5,
            'baggage_capacity' => 3,
            'base_hourly_rate' => 180.00,
            'base_daily_rate' => 3200.00,
            'security_deposit' => 6000.00,
            'excess_km_rate' => 15.00,
            'excess_hour_rate' => 200.00,
            'free_km_per_day' => 300,
        ]);

        $catMUV = VehicleCategory::create([
            'company_id' => $company->id,
            'name' => 'Premium MUV',
            'slug' => 'premium-muv',
            'description' => 'Spacious 7/8 seater road-trip king for families and chauffeur journeys.',
            'seating_capacity' => 7,
            'baggage_capacity' => 4,
            'base_hourly_rate' => 250.00,
            'base_daily_rate' => 4500.00,
            'security_deposit' => 10000.00,
            'excess_km_rate' => 18.00,
            'excess_hour_rate' => 300.00,
            'free_km_per_day' => 300,
        ]);

        $catLuxury = VehicleCategory::create([
            'company_id' => $company->id,
            'name' => 'Luxury Sedan',
            'slug' => 'luxury-sedan',
            'description' => 'German engineered luxury for weddings, VIP corporate visits, and elite travel.',
            'seating_capacity' => 5,
            'baggage_capacity' => 3,
            'base_hourly_rate' => 600.00,
            'base_daily_rate' => 11000.00,
            'security_deposit' => 25000.00,
            'excess_km_rate' => 40.00,
            'excess_hour_rate' => 800.00,
            'free_km_per_day' => 200,
        ]);

        // 7. Vehicle Brands & Models
        $brandMaruti = VehicleBrand::create(['name' => 'Maruti Suzuki']);
        $brandHyundai = VehicleBrand::create(['name' => 'Hyundai']);
        $brandMahindra = VehicleBrand::create(['name' => 'Mahindra']);
        $brandToyota = VehicleBrand::create(['name' => 'Toyota']);
        $brandTata = VehicleBrand::create(['name' => 'Tata Motors']);
        $brandBMW = VehicleBrand::create(['name' => 'BMW']);

        $modelSwift = VehicleModel::create(['brand_id' => $brandMaruti->id, 'category_id' => $catHatchback->id, 'name' => 'Swift ZXi', 'body_type' => 'Hatchback']);
        $modelDzire = VehicleModel::create(['brand_id' => $brandMaruti->id, 'category_id' => $catSedan->id, 'name' => 'Dzire VXi', 'body_type' => 'Sedan']);
        $modelNexon = VehicleModel::create(['brand_id' => $brandTata->id, 'category_id' => $catCompactSUV->id, 'name' => 'Nexon Fearless EV', 'body_type' => 'Compact SUV']);
        $modelScorpio = VehicleModel::create(['brand_id' => $brandMahindra->id, 'category_id' => $catCompactSUV->id, 'name' => 'Scorpio-N Z8L', 'body_type' => 'Full SUV']);
        $modelInnova = VehicleModel::create(['brand_id' => $brandToyota->id, 'category_id' => $catMUV->id, 'name' => 'Innova Crysta 2.4 VX', 'body_type' => 'MUV']);
        $modelHycross = VehicleModel::create(['brand_id' => $brandToyota->id, 'category_id' => $catMUV->id, 'name' => 'Innova Hycross Hybrid', 'body_type' => 'MUV']);
        $modelBMW3 = VehicleModel::create(['brand_id' => $brandBMW->id, 'category_id' => $catLuxury->id, 'name' => '330Li Gran Limousine', 'body_type' => 'Luxury Sedan']);

        // 8. Physical Fleet
        $vehiclesData = [
            [
                'company_id' => $company->id,
                'branch_id' => $branchT2->id,
                'category_id' => $catHatchback->id,
                'model_id' => $modelSwift->id,
                'registration_number' => 'MH 02 FB 4521',
                'vin_number' => 'MA3ERFB1S00123451',
                'engine_number' => 'K12M1928371',
                'color' => 'Pearl White',
                'manufacture_year' => 2024,
                'fuel_type' => 'petrol',
                'transmission' => 'manual',
                'seating_capacity' => 5,
                'current_odometer' => 14200,
                'fuel_tank_capacity' => 37,
                'current_fuel_level' => 8.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locT2->id,
                'daily_rental_price' => 1800.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(8),
                'fitness_expiry_date' => Carbon::now()->addYears(2),
                'puc_expiry_date' => Carbon::now()->addMonths(4),
                'is_gps_enabled' => true,
                'features' => ['Air Conditioner', 'Dual Airbags', 'Touchscreen Infotainment', 'Apple CarPlay'],
            ],
            [
                'company_id' => $company->id,
                'branch_id' => $branchT2->id,
                'category_id' => $catSedan->id,
                'model_id' => $modelDzire->id,
                'registration_number' => 'MH 02 EC 8812',
                'vin_number' => 'MA3ERFB1S00123452',
                'engine_number' => 'K12M1928372',
                'color' => 'Magma Grey',
                'manufacture_year' => 2023,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'seating_capacity' => 5,
                'current_odometer' => 28400,
                'fuel_tank_capacity' => 37,
                'current_fuel_level' => 7.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locT2->id,
                'daily_rental_price' => 2400.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(6),
                'fitness_expiry_date' => Carbon::now()->addYears(1),
                'puc_expiry_date' => Carbon::now()->addMonths(2),
                'is_gps_enabled' => true,
                'features' => ['Air Conditioner', 'Rear AC Vents', 'Reverse Camera', 'Hill Hold Assist'],
            ],
            [
                'company_id' => $company->id,
                'branch_id' => $branchBandra->id,
                'category_id' => $catCompactSUV->id,
                'model_id' => $modelScorpio->id,
                'registration_number' => 'MH 01 DK 9004',
                'vin_number' => 'MA1TK2MCG0054321',
                'engine_number' => 'MHAWK22D884',
                'color' => 'Deep Forest Green',
                'manufacture_year' => 2024,
                'fuel_type' => 'diesel',
                'transmission' => 'automatic',
                'seating_capacity' => 7,
                'current_odometer' => 19500,
                'fuel_tank_capacity' => 57,
                'current_fuel_level' => 8.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locBandra->id,
                'daily_rental_price' => 3500.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(11),
                'fitness_expiry_date' => Carbon::now()->addYears(2),
                'puc_expiry_date' => Carbon::now()->addMonths(5),
                'is_gps_enabled' => true,
                'features' => ['4Xplor Terrain Modes', 'Sunroof', 'Wireless Charging', 'Sony 12-Speaker Sound'],
            ],
            [
                'company_id' => $company->id,
                'branch_id' => $branchT2->id,
                'category_id' => $catMUV->id,
                'model_id' => $modelInnova->id,
                'registration_number' => 'MH 04 KL 1008',
                'vin_number' => 'MBJ11GG830098765',
                'engine_number' => '2GD129482',
                'color' => 'Silver Metallic',
                'manufacture_year' => 2023,
                'fuel_type' => 'diesel',
                'transmission' => 'manual',
                'seating_capacity' => 7,
                'current_odometer' => 45200,
                'fuel_tank_capacity' => 55,
                'current_fuel_level' => 8.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locT2->id,
                'daily_rental_price' => 4500.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(4),
                'fitness_expiry_date' => Carbon::now()->addYears(1),
                'puc_expiry_date' => Carbon::now()->addMonths(3),
                'is_gps_enabled' => true,
                'features' => ['Captain Seats', 'Dual Climate AC', 'Eco/Power Drive Modes', 'Cruise Control'],
            ],
            [
                'company_id' => $company->id,
                'branch_id' => $branchBandra->id,
                'category_id' => $catLuxury->id,
                'model_id' => $modelBMW3->id,
                'registration_number' => 'MH 01 EV 0007',
                'vin_number' => 'WBA5R11030FL98214',
                'engine_number' => 'B48B20B9912',
                'color' => 'Carbon Black',
                'manufacture_year' => 2024,
                'fuel_type' => 'petrol',
                'transmission' => 'automatic',
                'seating_capacity' => 5,
                'current_odometer' => 8900,
                'fuel_tank_capacity' => 59,
                'current_fuel_level' => 8.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locBandra->id,
                'daily_rental_price' => 11000.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(10),
                'fitness_expiry_date' => Carbon::now()->addYears(2),
                'puc_expiry_date' => Carbon::now()->addMonths(6),
                'is_gps_enabled' => true,
                'features' => ['Panoramic Sunroof', 'Harman Kardon Sound', 'Ambient Lighting', 'Live Cockpit Professional'],
            ],
            [
                'company_id' => $company->id,
                'branch_id' => $branchPune->id,
                'category_id' => $catMUV->id,
                'model_id' => $modelHycross->id,
                'registration_number' => 'MH 12 QQ 5511',
                'vin_number' => 'MBJ11GG830098999',
                'engine_number' => 'M20AFXS1829',
                'color' => 'Blackish Ageha',
                'manufacture_year' => 2024,
                'fuel_type' => 'hybrid',
                'transmission' => 'automatic',
                'seating_capacity' => 7,
                'current_odometer' => 12100,
                'fuel_tank_capacity' => 52,
                'current_fuel_level' => 8.00,
                'status' => 'available',
                'ownership_type' => 'owned',
                'current_location_id' => $locPune->id,
                'daily_rental_price' => 5200.00,
                'insurance_expiry_date' => Carbon::now()->addMonths(9),
                'fitness_expiry_date' => Carbon::now()->addYears(2),
                'puc_expiry_date' => Carbon::now()->addMonths(5),
                'is_gps_enabled' => true,
                'features' => ['Ottoman Captain Seats', 'Hybrid EV Mode', 'ADAS Safety Suite', 'Ventilated Front Seats'],
            ]
        ];

        foreach ($vehiclesData as $v) {
            Vehicle::create($v);
        }

        // 9. Rental Types
        $rtSelfDrive = RentalType::create([
            'name' => 'Self-Drive Rental',
            'code' => 'self_drive',
            'description' => 'Customer drives the vehicle. Requires valid Driving License and security deposit.',
            'is_chauffeur_included' => false,
            'requires_dl' => true,
            'gst_rate_percent' => 18.00,
            'sac_code' => '997311',
        ]);

        $rtChauffeur = RentalType::create([
            'name' => 'Chauffeur-Driven City',
            'code' => 'chauffeur_driven',
            'description' => 'Professional uniformed chauffeur provided with duty slip.',
            'is_chauffeur_included' => true,
            'requires_dl' => false,
            'gst_rate_percent' => 5.00,
            'sac_code' => '996601',
        ]);

        $rtOutstation = RentalType::create([
            'name' => 'Outstation Chauffeur Trip',
            'code' => 'outstation',
            'description' => 'Inter-city trip with per km billing, driver day & night batas, and toll reconciliation.',
            'is_chauffeur_included' => true,
            'requires_dl' => false,
            'gst_rate_percent' => 5.00,
            'sac_code' => '996601',
        ]);

        $rtAirport = RentalType::create([
            'name' => 'Airport Transfer',
            'code' => 'airport_transfer',
            'description' => 'Fixed flat-rate pickup and drop from airport terminals with flight tracking.',
            'is_chauffeur_included' => true,
            'requires_dl' => false,
            'gst_rate_percent' => 5.00,
            'sac_code' => '996601',
        ]);

        // 10. Rental Packages
        RentalPackage::create([
            'company_id' => $company->id,
            'category_id' => $catSedan->id,
            'name' => '8 Hours / 80 Km Local Package',
            'package_type' => 'local',
            'duration_hours' => 8,
            'included_km' => 80,
            'base_price' => 2200.00,
            'extra_km_price' => 14.00,
            'extra_hour_price' => 160.00,
            'driver_bata_included' => true,
        ]);

        RentalPackage::create([
            'company_id' => $company->id,
            'category_id' => $catMUV->id,
            'name' => 'Innova 8 Hours / 80 Km City Tour',
            'package_type' => 'local',
            'duration_hours' => 8,
            'included_km' => 80,
            'base_price' => 3800.00,
            'extra_km_price' => 19.00,
            'extra_hour_price' => 250.00,
            'driver_bata_included' => true,
        ]);

        // 11. Drivers
        $driverUser = User::create([
            'company_id' => $company->id,
            'branch_id' => $branchT2->id,
            'name' => 'Ramesh Patil',
            'email' => 'driver@drivesphere.com',
            'phone' => '+91 97690 44551',
            'password' => Hash::make('password123'),
            'role' => 'driver',
            'status' => 'active',
        ]);

        Driver::create([
            'company_id' => $company->id,
            'branch_id' => $branchT2->id,
            'user_id' => $driverUser->id,
            'driver_code' => 'DRV-101',
            'name' => 'Ramesh Patil',
            'phone' => '+91 97690 44551',
            'license_number' => 'MH02 20120045892',
            'license_type' => 'transport',
            'license_expiry_date' => Carbon::now()->addYears(3),
            'badge_number' => 'BOM/TR/94821',
            'badge_expiry_date' => Carbon::now()->addYears(2),
            'police_verification_status' => 'verified',
            'daily_bata_rate' => 350.00,
            'night_bata_rate' => 350.00,
            'overtime_hourly_rate' => 70.00,
            'status' => 'available',
            'rating' => 4.95,
            'total_trips' => 142,
            'city' => 'Mumbai',
        ]);

        // 12. Customers
        $customerUser = User::create([
            'company_id' => $company->id,
            'branch_id' => $branchBandra->id,
            'name' => 'Aditya Singhania',
            'email' => 'customer@drivesphere.com',
            'phone' => '+91 99300 88776',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        $customer1 = Customer::create([
            'company_id' => $company->id,
            'user_id' => $customerUser->id,
            'customer_code' => 'CUST-0001',
            'customer_type' => 'individual',
            'name' => 'Aditya Singhania',
            'email' => 'customer@drivesphere.com',
            'phone' => '+91 99300 88776',
            'gender' => 'Male',
            'date_of_birth' => '1992-05-14',
            'address' => 'Flat 802, Ocean Crest, Worli Sea Face',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400018',
            'id_proof_type' => 'aadhaar',
            'id_proof_number' => '8492-3819-2041',
            'driving_license_number' => 'MH01 20140028911',
            'driving_license_expiry' => Carbon::now()->addYears(5),
            'is_dl_verified' => true,
            'status' => 'active',
            'loyalty_points' => 350,
        ]);

        CustomerDocument::create([
            'customer_id' => $customer1->id,
            'document_type' => 'driving_license_front',
            'document_number' => 'MH01 20140028911',
            'expiry_date' => Carbon::now()->addYears(5),
            'file_path' => 'documents/dl_sample_verified.png',
            'verification_status' => 'verified',
            'verified_by' => $adminUser->id,
            'verified_at' => now(),
        ]);

        // 13. Chart of Accounts
        $accounts = [
            ['code' => '1001', 'name' => 'Cash in Hand (Counter)', 'type' => 'asset'],
            ['code' => '1002', 'name' => 'HDFC Current Bank Account', 'type' => 'asset'],
            ['code' => '1003', 'name' => 'Accounts Receivable (Customers)', 'type' => 'asset'],
            ['code' => '2001', 'name' => 'Customer Security Deposits Payable (Escrow)', 'type' => 'liability'],
            ['code' => '2002', 'name' => 'CGST Output Tax Payable (9%)', 'type' => 'liability'],
            ['code' => '2003', 'name' => 'SGST Output Tax Payable (9%)', 'type' => 'liability'],
            ['code' => '3001', 'name' => 'Share Capital / Owner Equity', 'type' => 'equity'],
            ['code' => '4001', 'name' => 'Self-Drive Vehicle Rental Income', 'type' => 'revenue'],
            ['code' => '4002', 'name' => 'Chauffeur Rental & Trip Income', 'type' => 'revenue'],
            ['code' => '4003', 'name' => 'Excess Kilometer & Late Fee Income', 'type' => 'revenue'],
            ['code' => '4004', 'name' => 'Damage Recovery Income', 'type' => 'revenue'],
            ['code' => '5001', 'name' => 'Vehicle Maintenance & Spare Parts', 'type' => 'expense'],
            ['code' => '5002', 'name' => 'Chauffeur Wages & Trip Batas', 'type' => 'expense'],
            ['code' => '5003', 'name' => 'Fleet Insurance Premiums', 'type' => 'expense'],
            ['code' => '5004', 'name' => 'Fuel & Toll Disbursements', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            ChartOfAccount::create([
                'company_id' => $company->id,
                'account_code' => $acc['code'],
                'account_name' => $acc['name'],
                'account_type' => $acc['type'],
                'is_system' => true,
                'is_active' => true,
            ]);
        }
    }
}
