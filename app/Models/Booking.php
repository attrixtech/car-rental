<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'pickup_datetime' => 'datetime',
        'return_datetime' => 'datetime',
        'actual_pickup_datetime' => 'datetime',
        'actual_return_datetime' => 'datetime',
        'base_rental_amount' => 'decimal:2',
        'driver_allowance_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_estimated_amount' => 'decimal:2',
        'security_deposit_amount' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function rentalType()
    {
        return $this->belongsTo(RentalType::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }

    public function assignedVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    public function assignedDriver()
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class, 'pickup_location_id');
    }

    public function returnLocation()
    {
        return $this->belongsTo(PickupLocation::class, 'return_location_id');
    }

    public function agreement()
    {
        return $this->hasOne(RentalAgreement::class);
    }

    public function handover()
    {
        return $this->hasOne(VehicleHandover::class);
    }

    public function vehicleReturn()
    {
        return $this->hasOne(VehicleReturn::class);
    }

    public function deposit()
    {
        return $this->hasOne(SecurityDeposit::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function settlement()
    {
        return $this->hasOne(RentalSettlement::class);
    }

    public function tripRecord()
    {
        return $this->hasOne(TripRecord::class);
    }

    public function damageReports()
    {
        return $this->hasMany(VehicleDamageReport::class);
    }

    public function isSelfDrive(): bool
    {
        return $this->rentalType?->code === 'self_drive';
    }

    public function isChauffeurDriven(): bool
    {
        return in_array($this->rentalType?->code, ['chauffeur_driven', 'airport_transfer', 'outstation']);
    }
}

