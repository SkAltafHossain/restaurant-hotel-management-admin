@extends('admin.layouts.app')
@section('title', 'Orders')
@push('styles')
<style>
    #editModal .modal-content {
        max-width: 500px;
    }
    #paymentModal .modal-content {
        max-width: 500px;
    }
    #addItemModal .modal-content {
        max-width: 600px;
    }
</style>
@endpush
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
            <p class="mt-1 text-sm text-gray-500">View and manage restaurant and hotel orders</p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Orders</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($orders as $order)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->user->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($order->final_amount, 2) }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : ($order->order_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->order_status === 'prepared' ? 'bg-purple-100 text-purple-800' : ($order->order_status === 'preparing' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))) }}">{{ ucfirst($order->order_status) }}</span></td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($order->payment_status) }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-green-600 hover:text-green-900 mr-3">View</a>
                    <button onclick="openEditModal({{ $order->id }}, '{{ $order->order_status }}', '{{ $order->payment_status }}')" class="text-green-600 hover:text-green-900 mr-3">Update Status</button>
                    @if($order->payment_status === 'unpaid' && $order->order_type === 'dine_in')
                    <button onclick="openAddItemModal({{ $order->id }})" class="text-purple-600 hover:text-purple-900 mr-3">Add Item</button>
                    @endif
                    @if($order->payment_status === 'unpaid')
                    @if($order->order_type === 'delivery')
                    <button onclick="openPaymentModal({{ $order->id }}, {{ $order->final_amount }}, '{{ $order->order_type }}')" class="text-green-600 hover:text-green-900">Collect Cash</button>
                    @else
                    <button onclick="openPaymentModal({{ $order->id }}, {{ $order->final_amount }}, '{{ $order->order_type }}')" class="text-green-600 hover:text-green-900">Pay</button>
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Edit Order Status Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Update Order Status</h2>
                <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="" method="POST" id="editForm" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editOrderId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                    <select name="order_status" id="editOrderStatus" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="pending">Pending</option>
                        <option value="preparing">Preparing</option>
                        <option value="prepared">Order Prepared</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select name="payment_status" id="editPaymentStatus" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Process Payment</h2>
                <button onclick="closeModal('paymentModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.orders.payment') }}" method="POST" id="paymentForm" class="p-6">
            @csrf
            <input type="hidden" name="order_id" id="paymentOrderId">
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Amount to Pay</p>
                    <p class="text-2xl font-bold text-blue-700" id="paymentAmount">₹0.00</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" id="paymentMethodSelect" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="cod" id="cod-option">💵 Cash on Delivery</option>
                        <option value="online">📱 Online Payment</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('paymentModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Complete Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Add Item to Order</h2>
                <button onclick="closeModal('addItemModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="{{ route('admin.orders.add-item') }}" method="POST" id="addItemForm" class="p-6">
            @csrf
            <input type="hidden" name="order_id" id="add-item-order-id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Item</label>
                    <select name="item_id" id="add-item-select" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                        <option value="">Select an item</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" value="1" min="1" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition shadow-sm">
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeModal('addItemModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition shadow-lg">
                    Add Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let menuItems = [];

    // Load menu items on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route('admin.api.items') }}')
            .then(response => response.json())
            .then(data => {
                menuItems = data;
                const select = document.getElementById('add-item-select');
                menuItems.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name + ' - ₹' + item.price;
                    select.appendChild(option);
                });
            });
    });

    function openEditModal(id, orderStatus, paymentStatus) {
        document.getElementById('editOrderId').value = id;
        document.getElementById('editOrderStatus').value = orderStatus;
        document.getElementById('editPaymentStatus').value = paymentStatus;
        document.getElementById('editForm').action = '{{ route('admin.orders.update', ':id') }}'.replace(':id', id);
        openModal('editModal');
    }

    function openPaymentModal(id, amount, orderType) {
        document.getElementById('paymentOrderId').value = id;
        document.getElementById('paymentAmount').textContent = '₹' + amount.toFixed(2);
        const modalTitle = document.querySelector('#paymentModal h2');
        const codOption = document.getElementById('cod-option');
        const paymentMethodSelect = document.getElementById('paymentMethodSelect');

        if (orderType === 'takeaway') {
            modalTitle.textContent = 'Process Payment';
            codOption.style.display = 'none';
            if (paymentMethodSelect.value === 'cod') {
                paymentMethodSelect.value = 'online';
            }
        } else if (orderType === 'delivery') {
            modalTitle.textContent = 'Collect Cash on Delivery';
            codOption.style.display = 'block';
        } else {
            modalTitle.textContent = 'Process Payment';
            codOption.style.display = 'block';
        }

        openModal('paymentModal');
    }

    function openAddItemModal(orderId) {
        document.getElementById('add-item-order-id').value = orderId;
        openModal('addItemModal');
    }
</script>
@endpush
