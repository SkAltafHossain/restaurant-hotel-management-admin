<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use App\Models\Table;

class WaiterController extends Controller
{
    public function index()
    {
        $myOrders = Order::with('user', 'table', 'orderItems.item')
            ->where('order_type', 'dine_in')
            ->whereIn('order_status', ['ready_to_serve', 'serving'])
            ->orderBy('created_at', 'asc')
            ->get();

        $tables = Table::where('status', '!=', 'maintenance')->get();

        return view('waiter.index', compact('myOrders', 'tables'));
    }

    public function markServed(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'completed']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as served.');
    }

    public function getTableOrders(Table $table)
    {
        $orders = Order::with('orderItems.item')
            ->where('table_id', $table->id)
            ->where('order_type', 'dine_in')
            ->whereIn('order_status', ['pending', 'preparing', 'ready_to_serve', 'serving'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
