@extends('admin.layouts.app')

@section('title', 'Add Inventory Item')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Add Inventory Item</h1>
    <a href="{{ route('admin.inventories.index') }}" class="text-blue-600 hover:text-blue-900">Back to Inventory</a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.inventories.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Item Name</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('item_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                <input type="number" name="current_stock" value="{{ old('current_stock') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('current_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Unit</label>
                <select name="unit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="kg">Kilograms (kg)</option>
                    <option value="pcs">Pieces (pcs)</option>
                    <option value="ltr">Liters (ltr)</option>
                </select>
                @error('unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Low Alert Quantity</label>
                <input type="number" name="low_alert_qty" value="{{ old('low_alert_qty', 10) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('low_alert_qty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Supplier Name</label>
                <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Add Item</button>
        </div>
    </form>
</div>
@endsection
