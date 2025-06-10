<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\JamuProduct;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Ditambahkan untuk 'with()'

class ProductionScheduleController extends Controller
{
    public function index()
    {
        $schedules = ProductionSchedule::with(['jamuProduct', 'recipe'])
            ->orderBy('scheduled_date', 'desc')
            ->paginate(10);

        return view('production.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $products = JamuProduct::all();
        $recipes = Recipe::with('jamuProduct')->get();

        return view('production.schedules.create', compact('products', 'recipes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jamu_product_id' => 'required|exists:jamu_products,id',
            'recipe_id' => 'required|exists:recipes,id',
            'scheduled_date' => 'required|date',
            'planned_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $recipe = Recipe::find($request->recipe_id);
        // Pastikan recipe ditemukan sebelum menghitung
        if (!$recipe) {
            return redirect()->back()->withErrors(['recipe_id' => 'Resep tidak ditemukan.'])->withInput();
        }
        $planned_batches = ceil($request->planned_quantity / $recipe->batch_size);

        ProductionSchedule::create([
            'jamu_product_id' => $request->jamu_product_id,
            'recipe_id' => $request->recipe_id,
            'scheduled_date' => $request->scheduled_date,
            'planned_quantity' => $request->planned_quantity,
            'planned_batches' => $planned_batches,
            'notes' => $request->notes,
        ]);

        return redirect()->route('production.schedules.index')
            ->with('success', 'Jadwal produksi berhasil dibuat');
    }

    public function show(ProductionSchedule $schedule)
    {
        $schedule->load(['jamuProduct', 'recipe.ingredients.rawMaterial', 'productionBatches']);

        // Hitung kebutuhan bahan baku
        $materialNeeds = $this->calculateMaterialNeeds($schedule);

        return view('production.schedules.show', compact('schedule', 'materialNeeds'));
    }

    private function calculateMaterialNeeds(ProductionSchedule $schedule)
    {
        $materials = [];

        // Pastikan recipe tidak null dan memiliki ingredients
        if ($schedule->recipe && $schedule->recipe->ingredients) {
            foreach ($schedule->recipe->ingredients as $ingredient) {
                // Pastikan rawMaterial ada sebelum mengakses propertinya
                if ($ingredient->rawMaterial) {
                    $totalNeeded = $ingredient->quantity * $schedule->planned_batches;
                    $materials[] = [
                        'material' => $ingredient->rawMaterial,
                        'needed_per_batch' => $ingredient->quantity,
                        'total_needed' => $totalNeeded,
                        'available_stock' => $ingredient->rawMaterial->current_stock, // Asumsi ada kolom current_stock di RawMaterial
                        'shortage' => max(0, $totalNeeded - $ingredient->rawMaterial->current_stock)
                    ];
                }
            }
        }
        return $materials;
    }

    public function edit(ProductionSchedule $productionSchedule)
    {
        // Ambil data produk jamu dan resep untuk dropdown
        $products = JamuProduct::all();
        $recipes = Recipe::with('jamuProduct')->get();

        // Sesuaikan nama variabel untuk view 'edit'
        $schedule = $productionSchedule; // Menggunakan $schedule untuk konsistensi di view

        return view('production.schedules.edit', compact('schedule', 'products', 'recipes'));
    }

    public function update(Request $request, ProductionSchedule $productionSchedule)
    {
        $request->validate([
            'jamu_product_id' => 'required|exists:jamu_products,id',
            'recipe_id' => 'required|exists:recipes,id',
            'scheduled_date' => 'required|date',
            'planned_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $recipe = Recipe::find($request->recipe_id);
        // Pastikan recipe ditemukan sebelum menghitung
        if (!$recipe) {
            return redirect()->back()->withErrors(['recipe_id' => 'Resep tidak ditemukan.'])->withInput();
        }
        $planned_batches = ceil($request->planned_quantity / $recipe->batch_size);

        $productionSchedule->update([
            'jamu_product_id' => $request->jamu_product_id,
            'recipe_id' => $request->recipe_id,
            'scheduled_date' => $request->scheduled_date,
            'planned_quantity' => $request->planned_quantity,
            'planned_batches' => $planned_batches, // Perbarui planned_batches
            'notes' => $request->notes,
        ]);

        return redirect()->route('production.schedules.show', $productionSchedule->id)
            ->with('success', 'Jadwal produksi berhasil diperbarui!');
    }

    public function destroy(ProductionSchedule $productionSchedule)
    {
        $productionSchedule->delete();

        return redirect()->route('production.schedules.index')
            ->with('success', 'Jadwal produksi berhasil dihapus!');
    }


    public function generateFromLowStock()
    {
        // Ambil produk jamu yang stoknya di bawah batas minimum
        $lowStockProducts = JamuProduct::whereColumn('current_stock', '<=', 'min_stock')->get();

        foreach ($lowStockProducts as $product) {
            // Asumsi produk jamu hanya memiliki satu resep utama, atau ambil yang pertama
            $recipe = $product->recipes()->first();

            if ($recipe) {
                // Hitung kuantitas yang dibutuhkan (misal: produksi hingga 2x min_stock)
                $neededQuantity = $product->min_stock * 2;
                // Hitung jumlah batch yang direncanakan berdasarkan batch_size resep
                $planned_batches = ceil($neededQuantity / $recipe->batch_size);

                ProductionSchedule::create([
                    'jamu_product_id' => $product->id,
                    'recipe_id' => $recipe->id,
                    'scheduled_date' => now()->addDays(1), // Jadwalkan untuk besok
                    'planned_quantity' => $neededQuantity,
                    'planned_batches' => $planned_batches,
                    'status' => 'scheduled', // Atur status default
                    'notes' => 'Auto-generated from low stock alert'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Jadwal produksi otomatis telah dibuat');
    }
}
