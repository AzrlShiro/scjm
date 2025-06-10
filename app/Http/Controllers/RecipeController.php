<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\JamuProduct;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Models\RawMaterial;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('jamuProduct')->paginate(10);
        return view('production.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $products = JamuProduct::all();
        $rawMaterials = RawMaterial::all();

        return view('production.recipes.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jamu_product_id' => 'required|exists:jamu_products,id',
            'name' => 'required|string|max:255',
            'batch_size' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.raw_material_id' => 'required|exists:raw_materials,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.1'
        ]);

        $recipe = Recipe::create($request->only(['jamu_product_id', 'name', 'batch_size', 'description', 'instructions']));

        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        return redirect()->route('production.recipes.index')
            ->with('success', 'Resep berhasil dibuat');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['jamuProduct', 'ingredients.rawMaterial']);
        return view('production.recipes.show', compact('recipe'));
    }
}

