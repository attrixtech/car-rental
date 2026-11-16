<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReturn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'return_datetime' => 'datetime',
        'fuel_level_end' => 'decimal:2',
        'cleaning_charge' => 'decimal:2',
        'fuel_shortfall_litres' => 'decimal:2',
        'fuel_shortfall_charge' => 'decimal:2',
        'cleaning_required' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function receivingOfficer()
    {
        return $this->belongsTo(User::class, 'receiving_officer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

