<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\ProductionSchedule;
use App\Models\JamuProduct;
use App\Models\Recipe;


class ProductionBatchController extends Controller
{
    public function index()
    {
        $batches = ProductionBatch::with(['productionSchedule.jamuProduct', 'recipe'])
            ->orderBy('production_date', 'desc')
            ->paginate(10);

        return view('production.batches.index', compact('batches'));
    }

    public function create(ProductionSchedule $schedule)
    {
        return view('production.batches.create', compact('schedule'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_schedule_id' => 'required|exists:production_schedules,id',
            'production_date' => 'required|date',
            'planned_quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date|after:production_date',
            'notes' => 'nullable|string'
        ]);

        $batch = ProductionBatch::create([
            'batch_number' => ProductionBatch::generateBatchNumber(),
            'production_schedule_id' => $request->production_schedule_id,
            'recipe_id' => ProductionSchedule::find($request->production_schedule_id)->recipe_id,
            'production_date' => $request->production_date,
            'planned_quantity' => $request->planned_quantity,
            'expiry_date' => $request->expiry_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('production.batches.show', $batch)
            ->with('success', 'Batch produksi berhasil dibuat');
    }

    public function show(ProductionBatch $batch)
    {
        $batch->load(['productionSchedule.jamuProduct', 'recipe.ingredients.rawMaterial']);

        return view('production.batches.show', compact('batch'));
    }

    public function complete(Request $request, ProductionBatch $batch)
    {
        $request->validate([
            'actual_quantity' => 'required|integer|min:0',
            'quality_status' => 'required|in:excellent,good,fair,poor',
            'notes' => 'nullable|string'
        ]);

        $batch->update([
            'actual_quantity' => $request->actual_quantity,
            'quality_status' => $request->quality_status,
            'status' => 'completed',
            'notes' => $request->notes,
        ]);

        // Update stok produk
        $product = $batch->productionSchedule->jamuProduct;
        $product->increment('current_stock', $request->actual_quantity);

        // Kurangi stok bahan baku
        foreach ($batch->recipe->ingredients as $ingredient) {
            $ingredient->rawMaterial->decrement('current_stock', $ingredient->quantity);
        }

        return redirect()->route('production.batches.index')
            ->with('success', 'Batch produksi berhasil diselesaikan');
    }
};
