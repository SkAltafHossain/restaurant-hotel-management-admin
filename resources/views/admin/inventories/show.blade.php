@extends('admin.layouts.app')

@section('title', 'Inventory Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $inventory->item_name }}</h1>
    <a href="{{ route('admin.inventories.index') }}" class="text-blue-600 hover:text-blue-900">Back to Inventory</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-gray-500">SKU</p>
            <p class="text-lg font-semibold">{{ $inventory->sku }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Current Stock</p>
            <p class="text-lg font-semibold {{ $inventory->isLowStock() ? 'text-red-600' : 'text-green-600' }}">{{ $inventory->current_stock }} {{ $inventory->unit }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Unit</p>
            <p class="text-lg font-semibold">{{ strtoupper($inventory->unit) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Low Alert Quantity</p>
            <p class="text-lg font-semibold">{{ $inventory->low_alert_qty }} {{ $inventory->unit }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Supplier</p>
            <p class="text-lg font-semibold">{{ $inventory->supplier_name ?? 'Not specified' }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-900">Stock Transactions</h2>
        <a href="{{ route('admin.inventory-transactions.create') }}?inventory_id={{ $inventory->id }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            Add Transaction
        </a>
    </div>
    @if($inventory->inventoryTransactions->count() > 0)
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($inventory->inventoryTransactions as $transaction)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->transaction_type === 'stock_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->reference ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-gray-500">No transactions recorded</p>
    @endif
</div>
@endsection
