<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $table = 'vehicle_maintenance';

    protected $guarded = ['id'];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function workOrders()
    {
        return $this->hasMany(MaintenanceWorkOrder::class, 'maintenance_id');
    }
}

