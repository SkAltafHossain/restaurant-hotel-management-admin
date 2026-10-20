@extends('admin.layouts.app')

@section('title', 'Tables')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tables</h1>
            <p class="mt-1 text-sm text-gray-500">Manage restaurant tables</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Table
        </button>
    </div>

    <!-- Tables Grid -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Tables</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($tables as $table)
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:shadow-lg transition-all duration-200 {{ $table->status === 'available' ? 'bg-white' : ($table->status === 'occupied' ? 'bg-red-50' : 'bg-yellow-50') }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $table->status === 'available' ? 'bg-green-100' : ($table->status === 'occupied' ? 'bg-red-100' : 'bg-yellow-100') }}">
                            <svg class="w-6 h-6 {{ $table->status === 'available' ? 'text-green-600' : ($table->status === 'occupied' ? 'text-red-600' : 'text-yellow-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $table->status === 'available' ? 'bg-green-100 text-green-800' : ($table->status === 'occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $table->status === 'reserved' ? 'Reserved' : ucfirst($table->status) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Table {{ $table->table_number }}</h3>
                    <p class="text-gray-600 mb-4">Capacity: {{ $table->capacity }} seats</p>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.tables.edit', $table) }}" class="flex-1 text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">Edit</a>
                        <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($tables->hasPages())
    <div class="mt-6">
        {{ $tables->links() }}
    </div>
    @endif
</div>

<!-- Create Table Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Create New Table</h2>
                <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.tables.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Table Number *</label>
                    <input type="text" name="table_number" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., 1, 2, 3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Seats) *</label>
                    <input type="number" name="capacity" required min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., 4">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Create Table
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
