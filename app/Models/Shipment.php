<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_code', 'distributor_id', 'delivery_schedule_id', 'order_date',
        'total_weight', 'total_value', 'total_items', 'priority',
        'special_instructions', 'status', 'shipped_at', 'delivered_at'
    ];

    protected $casts = [
        'order_date' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function deliverySchedule()
    {
        return $this->belongsTo(DeliverySchedule::class);
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function deliveryProof()
    {
        return $this->hasOne(DeliveryProof::class);
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class)->orderBy('created_at', 'desc');
    }
}
