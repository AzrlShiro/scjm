<?php

namespace App\Models; // ✅ tambahkan ini

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;         // ✅ pastikan semua model ini sudah di-import
use App\Models\RawMaterial;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id', 'raw_material_id', 'quantity'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
