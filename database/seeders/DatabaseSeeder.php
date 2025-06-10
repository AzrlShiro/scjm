<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RawMaterial;
use App\Models\JamuProduct;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\ProductionSchedule;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // SEED USER (CODE ASLI ANDA)
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // SEED DATA PRODUCTION MANAGEMENT
        $this->seedRawMaterials();
        $this->seedJamuProducts();
        $this->seedRecipes();
        $this->seedProductionSchedules();
    }

    private function seedRawMaterials()
    {
        $rawMaterials = [
            [
                'name' => 'Kunyit',
                'code' => 'RM001',
                'unit' => 'kg',
                'price_per_unit' => 15000,
                'current_stock' => 100,
                'min_stock' => 20
            ],
            [
                'name' => 'Jahe',
                'code' => 'RM002',
                'unit' => 'kg',
                'price_per_unit' => 12000,
                'current_stock' => 80,
                'min_stock' => 15
            ],
            [
                'name' => 'Asam Jawa',
                'code' => 'RM003',
                'unit' => 'kg',
                'price_per_unit' => 8000,
                'current_stock' => 50,
                'min_stock' => 10
            ],
            [
                'name' => 'Gula Aren',
                'code' => 'RM004',
                'unit' => 'kg',
                'price_per_unit' => 20000,
                'current_stock' => 60,
                'min_stock' => 15
            ],
            [
                'name' => 'Temulawak',
                'code' => 'RM005',
                'unit' => 'kg',
                'price_per_unit' => 18000,
                'current_stock' => 40,
                'min_stock' => 12
            ]
        ];

        foreach ($rawMaterials as $material) {
            RawMaterial::create($material);
        }
    }

    private function seedJamuProducts()
    {
        $products = [
            [
                'name' => 'Jamu Kunyit Asam',
                'code' => 'JP001',
                'description' => 'Jamu tradisional kunyit asam untuk kesehatan pencernaan',
                'type' => 'Minuman',
                'price' => 5000,
                'min_stock' => 50,
                'current_stock' => 25 // Sengaja di bawah min_stock
            ],
            [
                'name' => 'Jamu Beras Kencur',
                'code' => 'JP002',
                'description' => 'Jamu beras kencur untuk stamina dan kesehatan',
                'type' => 'Minuman',
                'price' => 4500,
                'min_stock' => 40,
                'current_stock' => 60
            ],
            [
                'name' => 'Jamu Temulawak',
                'code' => 'JP003',
                'description' => 'Jamu temulawak untuk kesehatan hati',
                'type' => 'Minuman',
                'price' => 5500,
                'min_stock' => 30,
                'current_stock' => 15 // Sengaja di bawah min_stock
            ]
        ];

        foreach ($products as $product) {
            JamuProduct::create($product);
        }
    }

    private function seedRecipes()
    {
        // Resep 1: Kunyit Asam
        $kunyitAsamProduct = JamuProduct::where('code', 'JP001')->first();
        $kunyitAsamRecipe = Recipe::create([
            'jamu_product_id' => $kunyitAsamProduct->id,
            'name' => 'Resep Kunyit Asam Standar',
            'description' => 'Resep standar untuk jamu kunyit asam',
            'batch_size' => 20, // 1 batch = 20 botol
            'instructions' => "1. Cuci dan bersihkan kunyit\n2. Rebus kunyit dengan 5 liter air selama 30 menit\n3. Tambahkan asam jawa dan gula aren\n4. Aduk rata dan masak 15 menit\n5. Saring dan dinginkan\n6. Kemas dalam botol steril"
        ]);

        // Bahan untuk resep kunyit asam (per batch)
        RecipeIngredient::create([
            'recipe_id' => $kunyitAsamRecipe->id,
            'raw_material_id' => 1, // Kunyit
            'quantity' => 2.5 // 2.5 kg per batch
        ]);

        RecipeIngredient::create([
            'recipe_id' => $kunyitAsamRecipe->id,
            'raw_material_id' => 3, // Asam Jawa
            'quantity' => 0.5 // 0.5 kg per batch
        ]);

        RecipeIngredient::create([
            'recipe_id' => $kunyitAsamRecipe->id,
            'raw_material_id' => 4, // Gula Aren
            'quantity' => 1.0 // 1 kg per batch
        ]);

        // Resep 2: Temulawak
        $temulawakProduct = JamuProduct::where('code', 'JP003')->first();
        $temulawakRecipe = Recipe::create([
            'jamu_product_id' => $temulawakProduct->id,
            'name' => 'Resep Temulawak Tradisional',
            'description' => 'Resep temulawak untuk kesehatan hati',
            'batch_size' => 15, // 1 batch = 15 botol
            'instructions' => "1. Bersihkan temulawak dan jahe\n2. Parut halus temulawak dan jahe\n3. Rebus dengan 4 liter air selama 45 menit\n4. Tambahkan gula aren\n5. Masak hingga mendidih\n6. Saring dan kemas"
        ]);

        RecipeIngredient::create([
            'recipe_id' => $temulawakRecipe->id,
            'raw_material_id' => 5, // Temulawak
            'quantity' => 3.0 // 3 kg per batch
        ]);

        RecipeIngredient::create([
            'recipe_id' => $temulawakRecipe->id,
            'raw_material_id' => 2, // Jahe
            'quantity' => 1.0 // 1 kg per batch
        ]);

        RecipeIngredient::create([
            'recipe_id' => $temulawakRecipe->id,
            'raw_material_id' => 4, // Gula Aren
            'quantity' => 1.5 // 1.5 kg per batch
        ]);
    }

    private function seedProductionSchedules()
    {
        // Jadwal produksi untuk produk yang stoknya rendah
        $lowStockProducts = JamuProduct::whereColumn('current_stock', '<=', 'min_stock')->get();

        foreach ($lowStockProducts as $product) {
            $recipe = $product->recipes()->first();

            if ($recipe) {
                $neededQuantity = $product->min_stock * 2; // Produksi 2x min stock
                $planned_batches = ceil($neededQuantity / $recipe->batch_size);

                ProductionSchedule::create([
                    'jamu_product_id' => $product->id,
                    'recipe_id' => $recipe->id,
                    'scheduled_date' => now()->addDays(1),
                    'planned_quantity' => $neededQuantity,
                    'planned_batches' => $planned_batches,
                    'status' => 'scheduled',
                    'notes' => 'Auto-generated dari stok rendah - Seeder'
                ]);
            }
        }

        // Jadwal produksi manual tambahan
        $manualSchedule = ProductionSchedule::create([
            'jamu_product_id' => 2, // Beras Kencur
            'recipe_id' => 1, // Pakai resep pertama untuk demo
            'scheduled_date' => now()->addDays(3),
            'planned_quantity' => 60,
            'planned_batches' => 3,
            'status' => 'scheduled',
            'notes' => 'Produksi rutin mingguan'
        ]);
    }
}
