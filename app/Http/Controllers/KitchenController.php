<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Models\Order;

class KitchenController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::with('user', 'table', 'orderItems.item')
            ->where('order_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $preparingOrders = Order::with('user', 'table', 'orderItems.item')
            ->where('order_status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();

        $readyOrders = Order::with('user', 'table', 'orderItems.item')
            ->whereIn('order_status', ['prepared', 'ready_to_serve', 'ready_for_pickup'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kitchen.index', compact('pendingOrders', 'preparingOrders', 'readyOrders'));
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
}
