@extends('admin.layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Daily Report - {{ $date }}</h1>
    <a href="{{ route('admin.reports.daily.export', ['date' => $date]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Export PDF</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Food Revenue</p>
        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalFoodRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Room Revenue</p>
        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalRoomRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Expenses</p>
        <p class="text-2xl font-bold text-red-600">₹{{ number_format($totalExpenses, 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Net Revenue</p>
        <p class="text-2xl font-bold {{ $netRevenue >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($netRevenue, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Orders ({{ $orders->count() }})</h2>
        <div class="max-h-64 overflow-y-auto">
            @foreach($orders as $order)
            <div class="border-b py-2">
                <p class="font-medium">Order #{{ $order->id }} - ₹{{ number_format($order->final_amount, 2) }}</p>
                <p class="text-sm text-gray-500">{{ $order->order_status }} - {{ $order->payment_status }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Expenses ({{ $expenses->count() }})</h2>
        <div class="max-h-64 overflow-y-auto">
            @foreach($expenses as $expense)
            <div class="border-b py-2">
                <p class="font-medium">{{ $expense->title }} - ₹{{ number_format($expense->amount, 2) }}</p>
                <p class="text-sm text-gray-500">{{ ucfirst($expense->category) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
