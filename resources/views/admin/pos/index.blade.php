@extends('admin.layouts.app')

@section('title', 'POS System')

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="max-w-full mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Point of Sale</h1>
            <p class="mt-1 text-sm text-gray-500">Create orders quickly and efficiently</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Items Selection -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Menu Items</h2>
                    <p class="text-green-100 text-sm">Click on items to add them to the order</p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Search menu items..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="items-grid">
                        @foreach($items as $item)
                        <div class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-green-500 hover:shadow-lg transition-all duration-200 pos-item bg-white" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}">
                            @if($item->image)
                            <div class="relative mb-3">
                                <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-32 object-cover rounded-lg" alt="{{ $item->name }}">
                                @if($item->is_available)
                                <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">Available</span>
                                @else
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Unavailable</span>
                                @endif
                            </div>
                            @else
                            <div class="w-full h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            @endif
                            <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2 h-10" title="{{ $item->name }}">{{ $item->name }}</h3>
                            <p class="text-lg font-bold text-green-600">₹{{ number_format($item->price, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Current Order</h2>
                    <p class="text-green-100 text-sm">Review and complete your order</p>
                </div>
                <form action="{{ route('admin.pos.store') }}" method="POST" id="pos-form" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Type *</label>
                        <select name="order_type" id="order_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="dine_in">🍽️ Dine In</option>
                            <option value="takeaway">🥡 Takeaway</option>
                            <option value="delivery">🚚 Delivery</option>
                        </select>
                    </div>
                    <div class="mb-4" id="table-section">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Table Selection *</label>
                        <select name="table_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="">Select Table</option>
                            @foreach($tables as $table)
                            <option value="{{ $table->id }}">Table {{ $table->table_number }} ({{ $table->capacity }} seats)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
                        <div id="cart-items" class="space-y-2 min-h-48 max-h-80 overflow-y-auto border-2 border-dashed border-gray-300 rounded-lg p-3 bg-gray-50">
                            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm">Click items to add to cart</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount (₹)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" name="discount" value="0" min="0" step="0.01" class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm" placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-4" id="payment-method-section" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" id="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                            <option value="cod" id="cod-option">💵 Cash on Delivery</option>
                            <option value="online">📱 Online Payment</option>
                        </select>
                    </div>

                    <div class="border-t-2 border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotal-amount" class="font-semibold">₹0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Discount</span>
                            <span id="discount-amount" class="font-semibold text-red-600">-₹0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold mb-4 bg-green-50 p-3 rounded-lg">
                            <span class="text-green-700">Total</span>
                            <span id="total-amount" class="text-green-700">₹0.00</span>
                        </div>
                        <button type="submit" id="create-order-btn" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg flex items-center justify-center font-semibold disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

// Handle order type change to show/hide table selection and payment method
document.getElementById('order_type').addEventListener('change', function() {
    const tableSection = document.getElementById('table-section');
    const tableSelect = tableSection.querySelector('select');
    const paymentSection = document.getElementById('payment-method-section');
    const paymentSelect = document.getElementById('payment_method');
    const codOption = document.getElementById('cod-option');

    if (this.value === 'dine_in') {
        tableSection.style.display = 'block';
        tableSelect.required = true;
        paymentSection.style.display = 'none';
        paymentSelect.required = false;
    } else if (this.value === 'takeaway') {
        tableSection.style.display = 'none';
        tableSelect.required = false;
        tableSelect.value = '';
        paymentSection.style.display = 'block';
        paymentSelect.required = true;
        codOption.style.display = 'none';
        if (paymentSelect.value === 'cod') {
            paymentSelect.value = 'online';
        }
    } else if (this.value === 'delivery') {
        tableSection.style.display = 'none';
        tableSelect.required = false;
        tableSelect.value = '';
        paymentSection.style.display = 'block';
        paymentSelect.required = true;
        codOption.style.display = 'block';
    }
});

// Initialize table section visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    const orderType = document.getElementById('order_type');
    const tableSection = document.getElementById('table-section');
    const tableSelect = tableSection.querySelector('select');
    const paymentSection = document.getElementById('payment-method-section');
    const paymentSelect = document.getElementById('payment_method');
    const codOption = document.getElementById('cod-option');
    const createOrderBtn = document.getElementById('create-order-btn');

    createOrderBtn.disabled = true;

    if (orderType.value === 'dine_in') {
        tableSection.style.display = 'block';
        tableSelect.required = true;
        paymentSection.style.display = 'none';
        paymentSelect.required = false;
    } else if (orderType.value === 'takeaway') {
        tableSection.style.display = 'none';
        tableSelect.required = false;
        tableSelect.value = '';
        paymentSection.style.display = 'block';
        paymentSelect.required = true;
        codOption.style.display = 'none';
        if (paymentSelect.value === 'cod') {
            paymentSelect.value = 'online';
        }
    } else if (orderType.value === 'delivery') {
        tableSection.style.display = 'none';
        tableSelect.required = false;
        tableSelect.value = '';
        paymentSection.style.display = 'block';
        paymentSelect.required = true;
        codOption.style.display = 'block';
    }
});

document.querySelectorAll('.pos-item').forEach(item => {
    item.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const price = parseFloat(this.dataset.price);

        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        updateCart();
    });
});

function updateCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('total-amount');
    const subtotalEl = document.getElementById('subtotal-amount');
    const discountEl = document.getElementById('discount-amount');
    const form = document.getElementById('pos-form');
    const discountInput = form.querySelector('input[name="discount"]');
    const createOrderBtn = document.getElementById('create-order-btn');

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-sm">Click items to add to cart</p>
            </div>
        `;
        totalEl.textContent = '₹0.00';
        subtotalEl.textContent = '₹0.00';
        discountEl.textContent = '-₹0.00';
        createOrderBtn.disabled = true;
        return;
    }

    createOrderBtn.disabled = false;

    let html = '';
    let subtotal = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        html += `
            <div class="flex justify-between items-center bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <p class="font-medium text-sm text-gray-900 line-clamp-1" title="${item.name}">${item.name}</p>
                    <p class="text-xs text-gray-500">₹${item.price.toFixed(2)} each</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-gray-100 border border-gray-300 rounded-lg">
                        <button type="button" onclick="updateQuantity(${index}, -1)" class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded-l-lg transition border-r border-gray-300 font-semibold">-</button>
                        <span class="px-3 py-1 font-semibold text-gray-900 min-w-[40px] text-center bg-white">${item.quantity}</span>
                        <button type="button" onclick="updateQuantity(${index}, 1)" class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded-r-lg transition border-l border-gray-300 font-semibold">+</button>
                    </div>
                    <span class="font-bold text-green-600 min-w-[60px] text-right">₹${itemTotal.toFixed(2)}</span>
                    <button type="button" onclick="removeItem(${index})" class="ml-2 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="items[${index}][item_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            </div>
        `;
    });

    container.innerHTML = html;

    const discount = parseFloat(discountInput.value) || 0;
    const total = Math.max(0, subtotal - discount);

    subtotalEl.textContent = '₹' + subtotal.toFixed(2);
    discountEl.textContent = '-₹' + discount.toFixed(2);
    totalEl.textContent = '₹' + total.toFixed(2);
}

function updateQuantity(index, change) {
    const item = cart[index];
    item.quantity += change;
    if (item.quantity <= 0) {
        removeItem(index);
    } else {
        updateCart();
    }
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

// Update totals when discount changes
document.querySelector('input[name="discount"]').addEventListener('input', updateCart);

// Search functionality
document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.pos-item');

    items.forEach(item => {
        const name = item.dataset.name.toLowerCase();
        if (name.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endsection
