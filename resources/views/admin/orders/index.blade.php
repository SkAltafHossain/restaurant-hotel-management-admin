@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage restaurant and hotel orders</p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Orders</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($orders as $order)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->user->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($order->final_amount, 2) }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : ($order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($order->order_status) }}</span></td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($order->payment_status) }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-green-600 hover:text-green-900 mr-3">View</a>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="text-green-600 hover:text-green-900">Update Status</a>
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
@endsection
