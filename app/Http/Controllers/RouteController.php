<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with('deliverySchedules')->paginate(10);
        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        return view('routes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:routes,code',
            'distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
        ]);

        Route::create($request->all());
        return redirect()->route('routes.index')->with('success', 'Rute berhasil ditambahkan');
    }

    public function show(Route $route)
    {
        $route->load('deliverySchedules.shipments');
        return view('routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        return view('routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:routes,code,' . $route->id,
            'distance' => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:0',
        ]);

        $route->update($request->all());
        return redirect()->route('routes.index')->with('success', 'Rute berhasil diperbarui');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->route('routes.index')->with('success', 'Rute berhasil dihapus');
    }
}
