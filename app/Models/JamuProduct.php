<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;
use App\Models\ProductionSchedule;

class JamuProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'type', 'price', 'min_stock', 'current_stock'
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function productionSchedules()
    {
        return $this->hasMany(ProductionSchedule::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }
};
