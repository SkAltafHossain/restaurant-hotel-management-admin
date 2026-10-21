<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Models\Order;

class DeliveryController extends Controller
{
    public function index()
    {
        $assignedOrders = Order::with('user', 'orderItems.item')
            ->where('assigned_to', auth()->id())
            ->whereIn('order_status', ['ready_for_pickup', 'out_for_delivery'])
            ->orderBy('created_at', 'asc')
            ->get();

        $availableOrders = Order::with('user', 'orderItems.item')
            ->where('order_type', 'delivery')
            ->where('order_status', 'ready_for_pickup')
            ->whereNull('assigned_to')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('delivery.index', compact('assignedOrders', 'availableOrders'));
    }

    public function assignOrder(Order $order)
    {
        $order->update(['assigned_to' => auth()->id()]);

        return back()->with('success', 'Order assigned to you.');
    }

    public function markOutForDelivery(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'out_for_delivery']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as out for delivery.');
    }

    public function markDelivered(Order $order)
    {
        $previousStatus = $order->order_status;
        $order->update(['order_status' => 'completed']);

        OrderStatusUpdated::dispatch($order, $previousStatus);

        return back()->with('success', 'Order marked as delivered.');
    }
}
