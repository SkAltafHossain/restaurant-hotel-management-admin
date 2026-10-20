@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Revenue (This Month)</p>
                <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex gap-4 text-sm">
            <div>
                <p class="text-gray-500">Food</p>
                <p class="font-semibold">₹{{ number_format($totalFoodRevenue, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Rooms</p>
                <p class="font-semibold">₹{{ number_format($totalRoomRevenue, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Room Occupancy</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($occupancyRate, 1) }}%</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Active Orders</p>
                <p class="text-2xl font-bold text-gray-900">{{ $activeOrders }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
        <div class="mt-4 text-sm">
            <p class="text-gray-500">Completed Today: {{ $completedOrdersToday }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingTableBookings + $confirmedRoomBookings }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex gap-4 text-sm">
            <div>
                <p class="text-gray-500">Tables</p>
                <p class="font-semibold">{{ $pendingTableBookings }}</p>
            </div>
            <div>
                <p class="text-gray-500">Rooms</p>
                <p class="font-semibold">{{ $confirmedRoomBookings }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockItems > 0)
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
    <div class="flex items-center">
        <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <p class="font-semibold text-red-800">Low Stock Alert</p>
            <p class="text-red-600">{{ $lowStockItems }} items are running low on stock. Check inventory for details.</p>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.pos.index') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span class="text-sm font-medium text-gray-900">New Order (POS)</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span class="text-sm font-medium text-gray-900">View Orders</span>
        </a>
        <a href="{{ route('admin.table-bookings.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
            <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-medium text-gray-900">Table Bookings</span>
        </a>
        <a href="{{ route('admin.room-bookings.index') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
            <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-sm font-medium text-gray-900">Room Bookings</span>
        </a>
    </div>
</div>
@endsection
