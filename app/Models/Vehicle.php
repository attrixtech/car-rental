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

    public function getImageUrlAttribute(): string
    {
        $primaryPhoto = $this->photos()->where('is_primary', true)->first() ?? $this->photos()->first();
        if ($primaryPhoto && !empty($primaryPhoto->file_path)) {
            return $primaryPhoto->file_path;
        }

        $modelName = strtolower($this->model?->name ?? '');
        $catSlug = strtolower($this->category?->slug ?? '');

        if (str_contains($modelName, 'swift')) {
            return 'https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'dzire')) {
            return 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'scorpio') || str_contains($modelName, 'thar')) {
            return 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'hycross')) {
            return 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'innova')) {
            return 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'bmw') || str_contains($modelName, '330') || str_contains($catSlug, 'luxury')) {
            return 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($modelName, 'nexon') || str_contains($modelName, 'ev')) {
            return 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=800&q=80';
        }

        if (str_contains($catSlug, 'suv')) {
            return 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($catSlug, 'muv')) {
            return 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80';
        } elseif (str_contains($catSlug, 'sedan')) {
            return 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
        }

        return 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80';
    }
}

