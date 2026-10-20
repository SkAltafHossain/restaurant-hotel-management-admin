@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-900">Back to Categories</a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="food" {{ $category->type === 'food' ? 'selected' : '' }}>Food</option>
                    <option value="room" {{ $category->type === 'room' ? 'selected' : '' }}>Room</option>
                </select>
                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @if($category->image)
                <p class="mt-1 text-sm text-gray-500">Current: {{ $category->image }}</p>
                @endif
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Slug will be automatically generated from the name.</p>
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Update Category</button>
        </div>
    </form>
</div>
@endsection
