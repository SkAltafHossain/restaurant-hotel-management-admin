@extends('admin.layouts.app')

@section('title', 'Inventory')

@push('styles')
<style>
    #createModal .modal-content {
        max-width: 600px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Inventory</h1>
            <p class="mt-1 text-sm text-gray-500">Manage restaurant inventory items</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Item
        </button>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Inventory Items</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($inventories as $inventory)
            <tr class="{{ $inventory->isLowStock() ? 'bg-red-50' : '' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inventory->item_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $inventory->sku }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $inventory->isLowStock() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $inventory->current_stock }} {{ $inventory->unit }}
                    </span>
                    @if($inventory->isLowStock())
                    <span class="ml-2 text-xs text-red-600">Low Stock!</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ strtoupper($inventory->unit) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $inventory->supplier_name ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.inventories.show', $inventory) }}" class="text-green-600 hover:text-green-900 mr-3">View</a>
                    <a href="{{ route('admin.inventories.edit', $inventory) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form action="{{ route('admin.inventories.destroy', $inventory) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($inventories->hasPages())
    <div class="mt-6">
        {{ $inventories->links() }}
    </div>
    @endif
</div>

<!-- Create Inventory Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Create New Inventory Item</h2>
                <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.inventories.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., Rice, Sugar, Oil">
                    @error('item_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., RIC-001">
                    @error('sku')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock *</label>
                        <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" required min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="0">
                        @error('current_stock')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                        <select name="unit" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                            <option value="ltr" {{ old('unit') == 'ltr' ? 'selected' : '' }}>Liter (ltr)</option>
                        </select>
                        @error('unit')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Low Alert Quantity *</label>
                    <input type="number" name="low_alert_qty" value="{{ old('low_alert_qty', 10) }}" required min="0"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., 10">
                    @error('low_alert_qty')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name</label>
                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., ABC Suppliers">
                    @error('supplier_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Create Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-open modal if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openModal('createModal');
        });
    @endif
</script>
@endpush
