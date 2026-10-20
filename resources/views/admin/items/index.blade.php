@extends('admin.layouts.app')

@section('title', 'Menu Items')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-container {
        min-height: 150px;
        border-radius: 0.375rem;
    }
    .ql-toolbar {
        border-radius: 0.375rem 0.375rem 0 0;
    }
    #createModal .modal-content {
        max-width: 700px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Menu Items</h1>
            <p class="mt-1 text-sm text-gray-500">Manage restaurant menu items and rooms</p>
        </div>
        <button onclick="openModal('createModal')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Item
        </button>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Items</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        @if($item->image)<img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $item->image) }}" alt="">@endif
                        <div class="ml-4"><div class="text-sm font-medium text-gray-900">{{ $item->name }}</div></div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->category->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($item->price, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->stock_qty }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $item->is_available ? 'Available' : 'Unavailable' }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.items.edit', $item) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="mt-6">
        {{ $items->links() }}
    </div>
    @endif
</div>

<!-- Create Item Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Create New Item</h2>
                <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="e.g., Grilled Chicken Salad">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ ucfirst($category->type) }})</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>Food</option>
                            <option value="room" {{ old('type') == 'room' ? 'selected' : '' }}>Room</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm"
                                placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                        <input type="number" name="stock_qty" value="{{ old('stock_qty', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm"
                            placeholder="0">
                        @error('stock_qty')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability Status</label>
                        <div class="flex items-center space-x-4 mt-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_available" value="1" {{ old('is_available', 1) == 1 ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Available</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_available" value="0" {{ old('is_available') == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Unavailable</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div id="editor" class="bg-white"></div>
                    <input type="hidden" name="description" id="description">
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer">
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                        <label for="image-input" class="cursor-pointer">
                            <div class="space-y-2">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium text-green-600 hover:text-green-500">Click to upload</span>
                                    or drag and drop
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </label>
                        <div id="image-preview" class="mt-4 hidden">
                            <img id="preview-img" class="mx-auto h-32 w-32 object-cover rounded-lg" src="" alt="Preview">
                        </div>
                    </div>
                    @error('image')
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
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Enter item description...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'clean']
            ]
        }
    });

    var form = document.querySelector('#createModal form');
    form.onsubmit = function() {
        document.querySelector('#description').value = quill.root.innerHTML;
    };

    // Image preview
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Auto-open modal if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openModal('createModal');
            // Restore Quill content if exists
            @if(old('description'))
                quill.root.innerHTML = {{ json_encode(old('description')) }};
            @endif
        });
    @endif
</script>
@endpush
