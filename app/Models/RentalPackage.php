<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'base_price' => 'decimal:2',
        'extra_km_price' => 'decimal:2',
        'extra_hour_price' => 'decimal:2',
        'driver_bata_included' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }
}

