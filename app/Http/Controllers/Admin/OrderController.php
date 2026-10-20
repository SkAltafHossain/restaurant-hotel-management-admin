<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'table')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(OrderRequest $request)
    {
        Order::create($request->validated());

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load('user', 'table', 'orderItems.item', 'transaction');

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return redirect()->route('admin.orders.index');
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->only(['order_status', 'payment_status']));

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cod,online,cash,upi,card',
        ]);

        $order = Order::find($request->order_id);
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'transaction_id' => 'PAY-'.time().'-'.$order->id,
            'amount' => $order->final_amount,
            'status' => 'completed',
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Payment completed successfully.');
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::find($request->order_id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('admin.orders.index')->with('error', 'Cannot add items to paid orders.');
        }

        if ($order->order_type !== 'dine_in') {
            return redirect()->route('admin.orders.index')->with('error', 'Can only add items to dine-in orders.');
        }

        $item = Item::find($request->item_id);
        $lineTotal = $item->price * $request->quantity;

        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'quantity' => $request->quantity,
            'price' => $item->price,
            'total_price' => $lineTotal,
        ]);

        $order->total_amount += $lineTotal;
        $order->final_amount = $order->total_amount - $order->discount;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Item added to order successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
