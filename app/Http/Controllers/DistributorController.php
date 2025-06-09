<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::paginate(10);
        return view('distributors.index', compact('distributors'));
    }

    public function create()
    {
        return view('distributors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:distributors,code',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        Distributor::create($request->all());
        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil ditambahkan');
    }

    public function show(Distributor $distributor)
    {
        $distributor->load('shipments.items.product');
        return view('distributors.show', compact('distributor'));
    }

    public function edit(Distributor $distributor)
    {
        return view('distributors.edit', compact('distributor'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:distributors,code,' . $distributor->id,
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $distributor->update($request->all());
        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil diperbarui');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil dihapus');
    }
}
