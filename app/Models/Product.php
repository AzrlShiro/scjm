<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'weight', 'price', 'stock_quantity', 'unit'
    ];

    public function shipmentItems()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}
