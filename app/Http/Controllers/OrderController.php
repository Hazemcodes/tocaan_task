<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with('items')->paginate(10);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total price
        $totalPrice = 0;
        $itemsData = [];

        foreach ($request->items as $itemData) {
            $item = Item::findOrFail($itemData['id']);
            $totalPrice += $item->price * $itemData['quantity'];

            // Store item details in order
            $itemsData[$item->id] = [
                'quantity' => $itemData['quantity'],
                'price' => $item->price,
            ];
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);

        // Attach items to order
        $order->items()->attach($itemsData);

        return response()->json(['message' => 'Order created successfully', 'order' => $order->load('items')], 201);
    }

    public function show($id)
    {
        $order = Order::with('items')->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'items' => 'sometimes|array',
            'items.*.id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        // Update order status if provided
        if ($request->has('status')) {
            $order->status = $request->status;
        }

        // Update items if provided
        if ($request->has('items')) {
            $totalPrice = 0;
            $itemsData = [];

            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                $totalPrice += $item->price * $itemData['quantity'];

                $itemsData[$item->id] = [
                    'quantity' => $itemData['quantity'],
                    'price' => $item->price,
                ];
            }

            $order->total_price = $totalPrice;
            $order->items()->sync($itemsData);
        }

        $order->save();

        return response()->json(['message' => 'Order updated successfully', 'order' => $order->load('items')]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Check if payments exist (Assuming a Payment model exists)
        if ($order->payments()->exists()) {
            return response()->json(['error' => 'Cannot delete order with associated payments'], 400);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
