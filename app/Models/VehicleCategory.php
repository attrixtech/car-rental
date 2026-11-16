<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'category_id');
    }

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'category_id');
    }

    public function ratePlans()
    {
        return $this->hasMany(RentalRatePlan::class, 'category_id');
    }
}

