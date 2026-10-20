@extends('admin.layouts.app')

@section('title', 'Rooms')

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
            <h1 class="text-3xl font-bold text-gray-900">Rooms</h1>
            <p class="mt-1 text-sm text-gray-500">Manage hotel rooms</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Room
        </button>
    </div>

    <!-- Rooms Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Rooms</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Floor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Night</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $room->room_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $room->category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $room->floor }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($room->price_per_night, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $room->max_adults }}A / {{ $room->max_children }}C</td>
                        <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : ($room->status === 'booked' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($room->status) }}</span></td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button></form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
{{ $rooms->links() }}
</div>

<!-- Create Room Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Create New Room</h2>
                <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.rooms.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Number *</label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="e.g., 101, 201">
                        @error('room_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Floor *</label>
                        <input type="number" name="floor" value="{{ old('floor') }}" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="e.g., 1, 2, 3">
                        @error('floor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night (₹) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night') }}" required
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                                placeholder="0.00">
                        </div>
                        @error('price_per_night')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Adults *</label>
                        <input type="number" name="max_adults" value="{{ old('max_adults') }}" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="e.g., 2">
                        @error('max_adults')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Children</label>
                        <input type="number" name="max_children" value="{{ old('max_children', 0) }}" min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="e.g., 0">
                        @error('max_children')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Create Room
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
