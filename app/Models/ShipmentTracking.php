<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'status', 'description', 'location',
        'latitude', 'longitude', 'updated_by'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
