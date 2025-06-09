<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_code', 'route_id', 'delivery_date', 'departure_time',
        'estimated_arrival_time', 'vehicle_type', 'vehicle_number',
        'driver_name', 'driver_phone', 'capacity_weight', 'notes', 'status'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'departure_time' => 'datetime:H:i',
        'estimated_arrival_time' => 'datetime:H:i',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
