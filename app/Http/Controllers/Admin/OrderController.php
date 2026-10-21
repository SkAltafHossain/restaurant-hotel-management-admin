<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderPlaced;
use App\Events\OrderStatusUpdated;
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
        $order = Order::create($request->validated());

        OrderPlaced::dispatch($order);

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
        $previousStatus = $order->order_status;
        $order->update($request->only(['order_status', 'payment_status', 'assigned_to']));

        if ($order->order_status !== $previousStatus) {
            OrderStatusUpdated::dispatch($order, $previousStatus);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cod,online,cash,upi,card',
        ]);

        $order = Order::find($request->order_id);

        if ($order->order_type === 'takeaway' && $request->payment_method === 'cod') {
            return back()->withErrors(['payment_method' => 'Cash on delivery is not available for takeaway orders. Please use online payment.'])->withInput();
        }

        // Only update payment status, not order status
        // Kitchen staff should still process the order even after payment
        $order->update([
            'payment_status' => 'paid',
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

    public function startPreparing(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'preparing']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as preparing.');
    }

    public function markReady(Order $order)
    {
        $previousStatus = $order->order_status;
        $newStatus = $order->order_type === 'dine_in' ? 'ready_to_serve' : 'ready_for_pickup';

        $order->update(['order_status' => $newStatus]);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as ready.');
    }

    public function markOutForDelivery(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'out_for_delivery']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as out for delivery.');
    }

    public function markCompleted(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'completed']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as completed.');
    }

    public function assignOrder(Request $request, Order $order)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $order->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', 'Order assigned successfully.');
    }
}
