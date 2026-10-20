<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RoomBooking;
use App\Models\Room;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $totalFoodRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$thisMonth, now()])
            ->sum('final_amount');

        $totalRoomRevenue = RoomBooking::where('payment_status', 'paid')
            ->whereBetween('created_at', [$thisMonth, now()])
            ->sum('total_price');

        $totalRevenue = $totalFoodRevenue + $totalRoomRevenue;

        $totalRooms = Room::count();
        $bookedRooms = Room::where('status', 'booked')->count();
        $occupancyRate = $totalRooms > 0 ? ($bookedRooms / $totalRooms) * 100 : 0;

        $activeOrders = Order::whereIn('order_status', ['pending', 'preparing'])->count();
        $completedOrdersToday = Order::where('order_status', 'completed')
            ->where('created_at', '>=', $today)
            ->count();

        $pendingTableBookings = \App\Models\TableBooking::where('status', 'pending')->count();
        $confirmedRoomBookings = RoomBooking::where('booking_status', 'confirmed')->count();

        $lowStockItems = Item::where('stock_qty', '<=', 10)->count();

        return view('admin.dashboard', compact(
            'totalFoodRevenue',
            'totalRoomRevenue',
            'totalRevenue',
            'occupancyRate',
            'activeOrders',
            'completedOrdersToday',
            'pendingTableBookings',
            'confirmedRoomBookings',
            'lowStockItems'
        ));
    }
}
