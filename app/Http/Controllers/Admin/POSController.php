<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Transaction;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $items = Item::where('is_available', true)->where('type', 'food')->get();
        $tables = Table::where('status', 'available')->get();
        return view('admin.pos.index', compact('items', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,upi,card',
        ]);

        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $itemModel = Item::find($item['item_id']);
            $lineTotal = $itemModel->price * $item['quantity'];
            $totalAmount += $lineTotal;

            $orderItems[] = [
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $itemModel->price,
                'total_price' => $lineTotal,
            ];
        }

        $discount = $request->discount ?? 0;
        $finalAmount = $totalAmount - $discount;

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_type' => $request->order_type,
            'table_id' => $request->table_id,
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'final_amount' => $finalAmount,
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        foreach ($orderItems as $orderItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $orderItem['item_id'],
                'quantity' => $orderItem['quantity'],
                'price' => $orderItem['price'],
                'total_price' => $orderItem['total_price'],
            ]);
        }

        Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'transaction_id' => 'POS-' . time() . '-' . $order->id,
            'amount' => $finalAmount,
            'status' => 'completed',
        ]);

        return redirect()->route('admin.pos.index')->with('success', 'Order completed successfully.');
    }
}
