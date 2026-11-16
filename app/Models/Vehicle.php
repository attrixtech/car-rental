<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'array',
        'insurance_expiry_date' => 'date',
        'fitness_expiry_date' => 'date',
        'permit_expiry_date' => 'date',
        'puc_expiry_date' => 'date',
        'is_gps_enabled' => 'boolean',
        'current_fuel_level' => 'decimal:2',
        'fuel_tank_capacity' => 'decimal:2',
        'daily_rental_price' => 'decimal:2',
        'hourly_rental_price' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function currentLocation()
    {
        return $this->belongsTo(PickupLocation::class, 'current_location_id');
    }

    public function documents()
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function photos()
    {
        return $this->hasMany(VehiclePhoto::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'assigned_vehicle_id');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    public function costLedgers()
    {
        return $this->hasMany(VehicleCostLedger::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}

