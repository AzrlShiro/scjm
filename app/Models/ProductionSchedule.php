<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamu_product_id', 'recipe_id', 'scheduled_date', 'planned_quantity',
        'planned_batches', 'status', 'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function jamuProduct()
    {
        return $this->belongsTo(JamuProduct::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }
};
