<?php

namespace App\Http\Controllers;

use App\Models\DeliverySchedule;
use App\Models\Route;
use Illuminate\Http\Request;

class DeliveryScheduleController extends Controller
{
    public function index()
    {
        $schedules = DeliverySchedule::with('route')->paginate(10);
        return view('delivery-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $routes = Route::where('status', 'active')->get();
        return view('delivery-schedules.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_code' => 'required|string|unique:delivery_schedules,schedule_code',
            'route_id' => 'required|exists:routes,id',
            'delivery_date' => 'required|date',
            'departure_time' => 'required',
            'estimated_arrival_time' => 'required',
            'vehicle_type' => 'required|string',
            'vehicle_number' => 'required|string',
            'driver_name' => 'required|string',
            'driver_phone' => 'required|string',
            'capacity_weight' => 'required|numeric|min:0',
        ]);

        DeliverySchedule::create($request->all());
        return redirect()->route('delivery-schedules.index')->with('success', 'Jadwal pengiriman berhasil ditambahkan');
    }

    public function show(DeliverySchedule $deliverySchedule)
    {
        $deliverySchedule->load('route', 'shipments.distributor');
        return view('delivery-schedules.show', compact('deliverySchedule'));
    }

    public function edit(DeliverySchedule $deliverySchedule)
    {
        $routes = Route::where('status', 'active')->get();
        return view('delivery-schedules.edit', compact('deliverySchedule', 'routes'));
    }

    public function update(Request $request, DeliverySchedule $deliverySchedule)
    {
        $request->validate([
            'schedule_code' => 'required|string|unique:delivery_schedules,schedule_code,' . $deliverySchedule->id,
            'route_id' => 'required|exists:routes,id',
            'delivery_date' => 'required|date',
            'departure_time' => 'required',
            'estimated_arrival_time' => 'required',
            'vehicle_type' => 'required|string',
            'vehicle_number' => 'required|string',
            'driver_name' => 'required|string',
            'driver_phone' => 'required|string',
            'capacity_weight' => 'required|numeric|min:0',
        ]);

        $deliverySchedule->update($request->all());
        return redirect()->route('delivery-schedules.index')->with('success', 'Jadwal pengiriman berhasil diperbarui');
    }

    public function destroy(DeliverySchedule $deliverySchedule)
    {
        $deliverySchedule->delete();
        return redirect()->route('delivery-schedules.index')->with('success', 'Jadwal pengiriman berhasil dihapus');
    }
}
