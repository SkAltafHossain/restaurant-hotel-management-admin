@extends('admin.layouts.app')
@section('title', 'Order Details')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-500">View order details and items</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            Back to Orders
        </a>
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Order Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="text-lg font-medium text-gray-900">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Type</p>
                    <p class="text-lg font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Status</p>
                    <p class="text-lg font-medium text-gray-900">
                        <span class="px-2 inline-flex text-xs rounded-full {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : ($order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->order_status === 'prepared' ? 'bg-purple-100 text-purple-800' : ($order->order_status === 'preparing' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))) }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <p class="text-lg font-medium text-gray-900">
                        <span class="px-2 inline-flex text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </p>
                </div>
                @if($order->order_type === 'delivery' && $order->transaction)
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="text-lg font-medium text-gray-900">
                        @if($order->transaction->payment_method === 'cod')
                        Cash on Delivery
                        @elseif($order->transaction->payment_method === 'online')
                        Online Payment
                        @else
                        {{ ucfirst($order->transaction->payment_method) }}
                        @endif
                    </p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-lg font-medium text-gray-900">₹{{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Discount</p>
                    <p class="text-lg font-medium text-gray-900">₹{{ number_format($order->discount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Final Amount</p>
                    <p class="text-lg font-bold text-green-600">₹{{ number_format($order->final_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Order Date</p>
                    <p class="text-lg font-medium text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($order->table)
                <div>
                    <p class="text-sm text-gray-500">Table</p>
                    <p class="text-lg font-medium text-gray-900">{{ $order->table->name }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Order Items</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $orderItem)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $orderItem->item->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $orderItem->quantity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">₹{{ number_format($orderItem->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">₹{{ number_format($orderItem->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
