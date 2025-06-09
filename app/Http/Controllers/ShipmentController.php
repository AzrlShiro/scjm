<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Distributor;
use App\Models\DeliverySchedule;
use App\Models\Product;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('distributor', 'deliverySchedule')->paginate(10);
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $distributors = Distributor::where('status', 'active')->get();
        $schedules = DeliverySchedule::where('status', 'scheduled')->get();
        $products = Product::all();
        return view('shipments.create', compact('distributors', 'schedules', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_code' => 'required|string|unique:shipments,shipment_code',
            'distributor_id' => 'required|exists:distributors,id',
            'delivery_schedule_id' => 'required|exists:delivery_schedules,id',
            'order_date' => 'required|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $shipment = Shipment::create($request->only([
            'shipment_code', 'distributor_id', 'delivery_schedule_id',
            'order_date', 'priority', 'special_instructions'
        ]));

        $totalWeight = 0;
        $totalValue = 0;
        $totalItems = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $itemWeight = $product->weight * $item['quantity'];
            $itemPrice = $product->price * $item['quantity'];

            $shipment->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total_price' => $itemPrice,
                'weight' => $itemWeight,
                'notes' => $item['notes'] ?? null,
            ]);

            $totalWeight += $itemWeight;
            $totalValue += $itemPrice;
            $totalItems += $item['quantity'];
        }

        $shipment->update([
            'total_weight' => $totalWeight,
            'total_value' => $totalValue,
            'total_items' => $totalItems,
        ]);

        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil ditambahkan');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('distributor', 'deliverySchedule.route', 'items.product', 'deliveryProof', 'trackings');
        return view('shipments.show', compact('shipment'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,packed,shipped,in_transit,delivered,cancelled',
            'description' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $shipment->update(['status' => $request->status]);

        if ($request->status === 'shipped') {
            $shipment->update(['shipped_at' => now()]);
        } elseif ($request->status === 'delivered') {
            $shipment->update(['delivered_at' => now()]);
        }

        $shipment->trackings()->create([
            'status' => $request->status,
            'description' => $request->description,
            'location' => $request->location,
            'updated_by' => auth()->user()->name ?? 'System',
        ]);

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui');
    }
}
