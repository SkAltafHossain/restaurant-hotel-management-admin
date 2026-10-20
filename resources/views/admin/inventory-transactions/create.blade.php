@extends('admin.layouts.app')

@section('title', 'Add Stock Transaction')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Add Stock Transaction</h1>
    <a href="{{ route('admin.inventories.index') }}" class="text-blue-600 hover:text-blue-900">Back to Inventory</a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.inventory-transactions.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Inventory Item</label>
                <select name="inventory_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Item</option>
                    @foreach($inventories as $inventory)
                    <option value="{{ $inventory->id }}" {{ request('inventory_id') == $inventory->id ? 'selected' : '' }}>{{ $inventory->item_name }} ({{ $inventory->current_stock }} {{ $inventory->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Transaction Type</label>
                <select name="transaction_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="stock_in">Stock In</option>
                    <option value="stock_out">Stock Out</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Reference (Optional)</label>
                <input type="text" name="reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Record Transaction</button>
        </div>
    </form>
</div>
@endsection
