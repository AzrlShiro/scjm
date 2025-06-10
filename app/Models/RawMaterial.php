<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'unit', 'price_per_unit', 'current_stock', 'min_stock'
    ];

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }
};
