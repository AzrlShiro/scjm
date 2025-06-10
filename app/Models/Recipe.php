<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductionSchedule;
use App\Models\ProductionBatch;
use App\Models\RecipeIngredient;
class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamu_product_id', 'name', 'description', 'batch_size', 'instructions'
    ];

    public function jamuProduct()
    {
        return $this->belongsTo(JamuProduct::class);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function productionSchedules()
    {
        return $this->hasMany(ProductionSchedule::class);
    }

    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
};
