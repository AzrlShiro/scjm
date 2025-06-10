<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\DeliveryScheduleController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DeliveryProofController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JamuProductController; // Asumsi Anda punya controller ini
use App\Http\Controllers\RawMaterialController; // Asumsi Anda punya controller ini
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductionScheduleController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\RecipeController;

Route::get('/homepage', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('distributors', DistributorController::class);
Route::resource('routes', RouteController::class);
Route::resource('delivery-schedules', DeliveryScheduleController::class);
Route::resource('shipments', ShipmentController::class);

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');

Route::post('/aksiregis', [AuthController::class, 'registerAction'])->name('registrasi.action');
Route::get('/register',[AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/action',[AuthController::class, 'registerAction'])->name('registrasi.action');

Route::post('shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
Route::get('shipments/{shipment}/delivery-proof/create', [DeliveryProofController::class, 'create'])->name('delivery-proofs.create');
Route::post('shipments/{shipment}/delivery-proof', [DeliveryProofController::class, 'store'])->name('delivery-proofs.store');
Route::get('delivery-proofs/{deliveryProof}', [DeliveryProofController::class, 'show'])->name('delivery-proofs.show');

// Production Management Routes
Route::prefix('production')->name('production.')->group(function () {

    // Production Schedules
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [ProductionScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ProductionScheduleController::class, 'create'])->name('create');
        Route::post('/', [ProductionScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}', [ProductionScheduleController::class, 'show'])->name('show');
        Route::post('/generate-low-stock', [ProductionScheduleController::class, 'generateFromLowStock'])->name('generate-low-stock');
    });

    // Production Batches
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [ProductionBatchController::class, 'index'])->name('index');
        Route::get('/create/{schedule}', [ProductionBatchController::class, 'create'])->name('create');
        Route::post('/', [ProductionBatchController::class, 'store'])->name('store');
        Route::get('/{batch}', [ProductionBatchController::class, 'show'])->name('show');
        Route::patch('/{batch}/complete', [ProductionBatchController::class, 'complete'])->name('complete');
    });

    // Recipes
    Route::prefix('recipes')->name('recipes.')->group(function () {
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
    });
});
