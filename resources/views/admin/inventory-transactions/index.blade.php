@extends('admin.layouts.app')

@section('title', 'Stock Transactions')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stock Transactions</h1>
            <p class="mt-1 text-sm text-gray-500">View inventory stock movement history</p>
        </div>
    </div>

    <!-- Stock Transactions Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Transactions</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performed By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($transactions as $transaction)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $transaction->inventory->item_name }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $transaction->transaction_type === 'stock_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</span></td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $transaction->quantity }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->reference ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->performedBy->name ?? 'System' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
    @endif
@endsection
