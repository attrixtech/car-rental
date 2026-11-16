<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_chauffeur_included' => 'boolean',
        'requires_dl' => 'boolean',
        'gst_rate_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function ratePlans()
    {
        return $this->hasMany(RentalRatePlan::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

