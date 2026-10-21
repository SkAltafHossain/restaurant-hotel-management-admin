@extends('waiter.layout')

@section('title', 'My Orders')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Orders to Serve -->
    <div class="col-span-1 lg:col-span-2">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Orders Ready to Serve</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($myOrders as $order)
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-gray-800">#{{ $order->id }}</span>
                            @if($order->table)
                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded text-sm">
                                    Table {{ $order->table->table_number }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="space-y-2 mb-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ $item->quantity }}x {{ $item->item->name }}</span>
                                <span class="text-gray-500">${{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <div class="text-sm">
                            <span class="text-gray-500">Total:</span>
                            <span class="font-bold text-green-600">${{ number_format($order->final_amount, 2) }}</span>
                        </div>
                        <form action="{{ route('waiter.mark-served', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-bold">
                                Mark Served
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-8 bg-white rounded-lg">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No orders ready to serve</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Table Status -->
    <div class="col-span-1 lg:col-span-2">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Table Status</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($tables as $table)
                <div class="table-card bg-white rounded-lg shadow-md p-4 cursor-pointer"
                     onclick="loadTableOrders({{ $table->id }})"
                     data-table-id="{{ $table->id }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-lg font-bold text-gray-800">Table {{ $table->table_number }}</span>
                        <span class="w-3 h-3 rounded-full
                            @if($table->status === 'available') bg-green-500
                            @elseif($table->status === 'occupied') bg-red-500
                            @else bg-yellow-500
                            @endif"></span>
                    </div>
                    <div class="text-sm text-gray-500 capitalize">{{ $table->status }}</div>
                    <div class="text-xs text-gray-400 mt-1">Capacity: {{ $table->capacity }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Table Orders Modal -->
<div id="tableOrdersModal" class="modal">
    <div class="modal-content">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Table Orders</h2>
                <button onclick="closeModal('tableOrdersModal')" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="tableOrdersContent" class="p-6">
            <p class="text-gray-500">Loading...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadTableOrders(tableId) {
        const modal = document.getElementById('tableOrdersModal');
        const content = document.getElementById('tableOrdersContent');

        openModal('tableOrdersModal');
        content.innerHTML = '<p class="text-gray-500">Loading...</p>';

        fetch(`/waiter/table/${tableId}/orders`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    content.innerHTML = '<p class="text-gray-500">No active orders for this table.</p>';
                    return;
                }

                let html = '<div class="space-y-4">';
                data.forEach(order => {
                    html += `
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-gray-800">#${order.id}</span>
                                <span class="px-2 py-1 text-xs rounded ${getStatusColor(order.order_status)}">
                                    ${formatStatus(order.order_status)}
                                </span>
                            </div>
                            <div class="space-y-1 text-sm">
                                ${order.order_items.map(item => `
                                    <div class="flex justify-between">
                                        <span>${item.quantity}x ${item.item.name}</span>
                                        <span class="text-gray-500">$${parseFloat(item.total_price).toFixed(2)}</span>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-200 text-sm">
                                <span class="text-gray-500">Total:</span>
                                <span class="font-bold text-green-600">$${parseFloat(order.final_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = '<p class="text-red-500">Error loading orders.</p>';
            });
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'preparing': 'bg-blue-100 text-blue-800',
            'ready_to_serve': 'bg-green-100 text-green-800',
            'serving': 'bg-purple-100 text-purple-800',
            'completed': 'bg-gray-100 text-gray-800',
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function formatStatus(status) {
        return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    }

    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
</script>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 50;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: white;
        border-radius: 1rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
<script>
    // Sound notification using Web Audio API
    function playNotificationSound() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    }

    // Listen for order updates
    Echo.channel('waiter.orders')
        .listen('.order.status.updated', (e) => {
            if (e.order.order_status === 'ready_to_serve') {
                playNotificationSound();
            }
            location.reload();
        });
</script>
@endpush
