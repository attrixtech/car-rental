<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalRatePlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'excess_km_rate' => 'decimal:2',
        'excess_hour_rate' => 'decimal:2',
        'security_deposit_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function rentalType()
    {
        return $this->belongsTo(RentalType::class, 'rental_type_id');
    }
}

