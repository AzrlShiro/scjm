<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'distance', 'estimated_duration', 'waypoints', 'status'
    ];

    protected $casts = [
        'waypoints' => 'array',
    ];

    public function deliverySchedules()
    {
        return $this->hasMany(DeliverySchedule::class);
    }
}
