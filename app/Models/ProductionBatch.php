<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number', 'production_schedule_id', 'recipe_id', 'production_date',
        'planned_quantity', 'actual_quantity', 'quality_status', 'expiry_date',
        'status', 'notes'
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function productionSchedule()
    {
        return $this->belongsTo(ProductionSchedule::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public static function generateBatchNumber()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'BATCH-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
};
