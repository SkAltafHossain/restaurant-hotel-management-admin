@extends('admin.layouts.app')

@section('title', 'Banners')

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
            <h1 class="text-3xl font-bold text-gray-900">Banners & Promotions</h1>
            <p class="mt-1 text-sm text-gray-500">Manage promotional banners</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Banner
        </button>
    </div>

    <!-- Banners Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Banners</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($banners as $banner)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $banner->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $banner->position_type)) }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $banner->status ? 'Active' : 'Inactive' }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($banners->hasPages())
    <div class="mt-6">
        {{ $banners->links() }}
    </div>
    @endif
</div>

<!-- Create Banner Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Create New Banner</h2>
                <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.banners.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., Summer Special">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL *</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="https://example.com/image.jpg">
                    @error('image_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target URL</label>
                    <input type="url" name="target_url" value="{{ old('target_url') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="https://example.com/page">
                    @error('target_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                    <select name="position_type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="home_slider" {{ old('position_type') == 'home_slider' ? 'selected' : '' }}>Home Slider</option>
                        <option value="app_popup" {{ old('position_type') == 'app_popup' ? 'selected' : '' }}>App Popup</option>
                    </select>
                    @error('position_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 rounded">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Create Banner
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
