@extends('admin.layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Item Details</h1>
    <a href="{{ route('admin.items.index') }}" class="text-blue-600 hover:text-blue-900">Back to Items</a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Name:</span>
                    <p class="text-gray-900">{{ $item->name }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Category:</span>
                    <p class="text-gray-900">{{ $item->category->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Type:</span>
                    <p class="text-gray-900">{{ ucfirst($item->type) }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Price:</span>
                    <p class="text-gray-900">₹{{ number_format($item->price, 2) }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Stock Quantity:</span>
                    <p class="text-gray-900">{{ $item->stock_qty }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Available:</span>
                    <p class="text-gray-900">{{ $item->is_available ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
            <div class="text-gray-900 max-w-none" style="line-height: 1.6;">
                @if($item->description)
                    {!! $item->description !!}
                @else
                    <p class="text-gray-500">No description</p>
                @endif
            </div>

            @if($item->image)
            <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Image</h3>
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-48 h-48 object-cover rounded-lg">
            @endif
        </div>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.items.edit', $item) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition mr-3">Edit</a>
        <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
</div>
@endsection
