<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleHandover extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'handover_datetime' => 'datetime',
        'fuel_level_start' => 'decimal:2',
        'cleanliness_interior' => 'boolean',
        'cleanliness_exterior' => 'boolean',
        'spare_tyre_present' => 'boolean',
        'jack_tools_present' => 'boolean',
        'documents_in_car' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

