<?php

namespace App\Http\Controllers;

use App\Models\DeliveryProof;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeliveryProofController extends Controller
{
    public function create(Shipment $shipment)
    {
        return view('delivery-proofs.create', compact('shipment'));
    }

    public function store(Request $request, Shipment $shipment)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_position' => 'required|string|max:255',
            'received_at' => 'required|date',
            'condition' => 'required|in:good,damaged,incomplete',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'recipient_name', 'recipient_position', 'received_at',
            'notes', 'latitude', 'longitude', 'condition', 'damage_description'
        ]);

        if ($request->hasFile('signature')) {
            $data['signature_path'] = $request->file('signature')->store('signatures', 'public');
        }

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('delivery-photos', 'public');
        }

        $shipment->deliveryProof()->create($data);
        $shipment->update(['status' => 'delivered', 'delivered_at' => $request->received_at]);

        $shipment->trackings()->create([
            'status' => 'delivered',
            'description' => 'Paket telah diterima oleh ' . $request->recipient_name,
            'location' => $request->location ?? 'Lokasi Tujuan',
            'updated_by' => auth()->user()->name ?? 'Driver',
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Bukti penerimaan berhasil ditambahkan');
    }

    public function show(DeliveryProof $deliveryProof)
    {
        $deliveryProof->load('shipment.distributor');
        return view('delivery-proofs.show', compact('deliveryProof'));
    }
}
