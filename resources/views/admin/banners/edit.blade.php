@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Banner</h1>
        <p class="mt-1 text-sm text-gray-500">Update banner information</p>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Edit Banner</h2>
        </div>
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="e.g., Summer Special">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image URL *</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $banner->image_url) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                        placeholder="https://example.com/image.jpg">
                    @error('image_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($banner->image_url)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-1">Current image:</p>
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-32 w-auto rounded border border-gray-200">
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target URL</label>
                    <input type="url" name="target_url" value="{{ old('target_url', $banner->target_url) }}"
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
                        <option value="home_slider" {{ old('position_type', $banner->position_type) == 'home_slider' ? 'selected' : '' }}>Home Slider</option>
                        <option value="app_popup" {{ old('position_type', $banner->position_type) == 'app_popup' ? 'selected' : '' }}>App Popup</option>
                    </select>
                    @error('position_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 rounded">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.banners.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Update Banner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
