<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'trip_start_datetime' => 'datetime',
        'trip_end_datetime' => 'datetime',
        'toll_parking_amount' => 'decimal:2',
        'driver_bata_amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

