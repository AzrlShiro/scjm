<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'recipient_name', 'recipient_position', 'received_at',
        'signature_path', 'photo_path', 'notes', 'latitude', 'longitude',
        'condition', 'damage_description'
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

