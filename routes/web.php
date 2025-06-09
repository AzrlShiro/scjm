<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\DeliveryScheduleController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\DeliveryProofController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('distributors', DistributorController::class);
Route::resource('routes', RouteController::class);
Route::resource('delivery-schedules', DeliveryScheduleController::class);
Route::resource('shipments', ShipmentController::class);

Route::post('shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
Route::get('shipments/{shipment}/delivery-proof/create', [DeliveryProofController::class, 'create'])->name('delivery-proofs.create');
Route::post('shipments/{shipment}/delivery-proof', [DeliveryProofController::class, 'store'])->name('delivery-proofs.store');
Route::get('delivery-proofs/{deliveryProof}', [DeliveryProofController::class, 'show'])->name('delivery-proofs.show');

