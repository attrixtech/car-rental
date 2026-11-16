<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalSettlement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'base_rental_charged' => 'decimal:2',
        'excess_km_charged' => 'decimal:2',
        'late_hours_charged' => 'decimal:2',
        'fuel_deficit_charged' => 'decimal:2',
        'damage_charges' => 'decimal:2',
        'toll_parking_charges' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'tax_charged' => 'decimal:2',
        'gross_final_amount' => 'decimal:2',
        'advance_paid' => 'decimal:2',
        'deposit_held' => 'decimal:2',
        'deposit_deductions' => 'decimal:2',
        'deposit_refund_amount' => 'decimal:2',
        'balance_payable_by_customer' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function settledByUser()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }
}

