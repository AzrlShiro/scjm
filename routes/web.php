<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\DeliveryScheduleController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DeliveryProofController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JamuProductController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductionScheduleController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\RecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register Routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard/Homepage
    Route::get('/homepage', function () {
        return view('dashboard');
    })->name('homepage');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Distribution Management Routes
    |--------------------------------------------------------------------------
    */

    // Distributors Management
    Route::resource('distributors', DistributorController::class);

    // Routes Management (renamed to avoid conflict with Laravel's Route class)
    Route::prefix('delivery-routes')->name('routes.')->group(function () {
        Route::get('/', [RouteController::class, 'index'])->name('index');
        Route::get('/create', [RouteController::class, 'create'])->name('create');
        Route::post('/', [RouteController::class, 'store'])->name('store');
        Route::get('/{route}', [RouteController::class, 'show'])->name('show');
        Route::get('/{route}/edit', [RouteController::class, 'edit'])->name('edit');
        Route::put('/{route}', [RouteController::class, 'update'])->name('update');
        Route::delete('/{route}', [RouteController::class, 'destroy'])->name('destroy');
    });

    // Delivery Schedules
    Route::resource('delivery-schedules', DeliveryScheduleController::class);

    // Shipments
    Route::resource('shipments', ShipmentController::class);
    Route::post('shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])
        ->name('shipments.update-status');

    // Delivery Proofs
    Route::prefix('delivery-proofs')->name('delivery-proofs.')->group(function () {
        Route::get('shipments/{shipment}/create', [DeliveryProofController::class, 'create'])->name('create');
        Route::post('shipments/{shipment}', [DeliveryProofController::class, 'store'])->name('store');
        Route::get('/{deliveryProof}', [DeliveryProofController::class, 'show'])->name('show');
        Route::get('/', [DeliveryProofController::class, 'index'])->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Production Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('production')->name('production.')->group(function () {

        // Production Schedules
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [ProductionScheduleController::class, 'index'])->name('index');
            Route::get('/create', [ProductionScheduleController::class, 'create'])->name('create');
            Route::post('/', [ProductionScheduleController::class, 'store'])->name('store');
            Route::get('/{schedule}', [ProductionScheduleController::class, 'show'])->name('show');
            Route::get('/{schedule}/edit', [ProductionScheduleController::class, 'edit'])->name('edit');
            Route::put('/{schedule}', [ProductionScheduleController::class, 'update'])->name('update');
            Route::delete('/{schedule}', [ProductionScheduleController::class, 'destroy'])->name('destroy');
            Route::post('/generate-low-stock', [ProductionScheduleController::class, 'generateFromLowStock'])
                ->name('generate-low-stock');
        });

        // Production Batches
        Route::prefix('batches')->name('batches.')->group(function () {
            Route::get('/', [ProductionBatchController::class, 'index'])->name('index');
            Route::get('/create/{schedule}', [ProductionBatchController::class, 'create'])->name('create');
            Route::post('/', [ProductionBatchController::class, 'store'])->name('store');
            Route::get('/{batch}', [ProductionBatchController::class, 'show'])->name('show');
            Route::get('/{batch}/edit', [ProductionBatchController::class, 'edit'])->name('edit');
            Route::put('/{batch}', [ProductionBatchController::class, 'update'])->name('update');
            Route::delete('/{batch}', [ProductionBatchController::class, 'destroy'])->name('destroy');
            Route::patch('/{batch}/complete', [ProductionBatchController::class, 'complete'])->name('complete');
        });

        // Recipes
        Route::prefix('recipes')->name('recipes.')->group(function () {
            Route::get('/', [RecipeController::class, 'index'])->name('index');
            Route::get('/create', [RecipeController::class, 'create'])->name('create');
            Route::post('/', [RecipeController::class, 'store'])->name('store');
            Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
            Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
            Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
            Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Product & Inventory Management Routes
    |--------------------------------------------------------------------------
    */

    // Jamu Products (if controller exists)
    Route::resource('products', JamuProductController::class)->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy'
    ]);

    // Raw Materials (if controller exists)
    Route::resource('raw-materials', RawMaterialController::class)->names([
        'index' => 'raw-materials.index',
        'create' => 'raw-materials.create',
        'store' => 'raw-materials.store',
        'show' => 'raw-materials.show',
        'edit' => 'raw-materials.edit',
        'update' => 'raw-materials.update',
        'destroy' => 'raw-materials.destroy'
    ]);

    // Ingredients (if controller exists)
    Route::resource('ingredients', IngredientController::class);

    /*
    |--------------------------------------------------------------------------
    | API-like Routes for AJAX calls
    |--------------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        // Get products for production
        Route::get('/products', [JamuProductController::class, 'getProducts'])->name('products');

        // Get ingredients for recipes
        Route::get('/ingredients', [IngredientController::class, 'getIngredients'])->name('ingredients');

        // Get distributors for delivery
        Route::get('/distributors', [DistributorController::class, 'getDistributors'])->name('distributors');
    });
});

/*
|--------------------------------------------------------------------------
| Password Reset Routes (Optional)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    // Add more password reset routes if needed
    // Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    // Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});
