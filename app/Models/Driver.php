<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'license_expiry_date' => 'date',
        'badge_expiry_date' => 'date',
        'daily_bata_rate' => 'decimal:2',
        'night_bata_rate' => 'decimal:2',
        'overtime_hourly_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'assigned_driver_id');
    }

    public function attendances()
    {
        return $this->hasMany(DriverAttendance::class);
    }

    public function allowances()
    {
        return $this->hasMany(DriverAllowance::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}

