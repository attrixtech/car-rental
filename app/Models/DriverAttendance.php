<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAttendance extends Model
{
    use HasFactory;

    protected $table = 'driver_attendance';

    protected $guarded = ['id'];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

