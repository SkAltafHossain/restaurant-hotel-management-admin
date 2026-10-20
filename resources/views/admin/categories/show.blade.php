@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-900">Back to Categories</a>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-gray-500">Type</p>
            <p class="text-lg font-semibold">{{ ucfirst($category->type) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Slug</p>
            <p class="text-lg font-semibold">{{ $category->slug }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Parent</p>
            <p class="text-lg font-semibold">{{ $category->parent ? $category->parent->name : 'None' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-lg font-semibold">{{ ucfirst($category->status) }}</p>
        </div>
        @if($category->image)
        <div class="col-span-2">
            <p class="text-sm text-gray-500">Image</p>
            <img src="{{ asset('storage/' . $category->image) }}" class="mt-2 h-32 w-32 object-cover rounded">
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Child Categories ({{ $category->children->count() }})</h2>
        @if($category->children->count() > 0)
        <ul class="space-y-2">
            @foreach($category->children as $child)
            <li class="text-gray-700">{{ $child->name }}</li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-500">No child categories</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Items ({{ $category->items->count() }})</h2>
        @if($category->items->count() > 0)
        <ul class="space-y-2">
            @foreach($category->items as $item)
            <li class="text-gray-700">{{ $item->name }} - ₹{{ number_format($item->price, 2) }}</li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-500">No items</p>
        @endif
    </div>
</div>
@endsection
