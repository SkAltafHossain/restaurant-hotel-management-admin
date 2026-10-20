@extends('admin.layouts.app')

@section('title', 'Edit Table')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Table</h1>
            <p class="mt-1 text-sm text-gray-500">Update table details</p>
        </div>
        <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tables
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-2xl">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Edit: Table {{ $table->table_number }}</h2>
            <p class="text-indigo-100 text-sm">Modify the table details below</p>
        </div>

        <form action="{{ route('admin.tables.update', $table) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Table Number *</label>
                    <input type="text" name="table_number" value="{{ old('table_number', $table->table_number) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., 1, 2, 3">
                    @error('table_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Seats) *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $table->capacity) }}" required min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., 4">
                    @error('capacity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm">
                        <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.tables.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Update Table
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
