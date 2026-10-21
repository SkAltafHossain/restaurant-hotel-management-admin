@extends('delivery.layout')

@section('title', 'Delivery Orders')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Available Orders -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Available Orders</h2>
        <div class="space-y-4">
            @forelse($availableOrders as $order)
                <div class="order-card bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-gray-800">#{{ $order->id }}</span>
                            <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">
                                Ready for Pickup
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @if($order->user)
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Customer:</span> {{ $order->user->name }}
                        </div>
                    @endif
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
                        <form action="{{ route('delivery.assign', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded text-sm font-bold">
                                Accept Order
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8 bg-white rounded-lg">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p>No available orders</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- My Assigned Orders -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">My Assigned Orders</h2>
        <div class="space-y-4">
            @forelse($assignedOrders as $order)
                <div class="order-card bg-white rounded-lg shadow-md p-4 border-l-4
                    @if($order->order_status === 'out_for_delivery') border-blue-500
                    @else border-green-500
                    @endif">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold text-gray-800">#{{ $order->id }}</span>
                            <span class="ml-2 px-2 py-1
                                @if($order->order_status === 'out_for_delivery') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif rounded text-sm">
                                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $order->updated_at->diffForHumans() }}</span>
                    </div>
                    @if($order->user)
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Customer:</span> {{ $order->user->name }}
                            @if($order->user->phone)
                                <span class="ml-2 text-gray-500">{{ $order->user->phone }}</span>
                            @endif
                        </div>
                    @endif
                    @if($order->user && $order->user->address)
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Address:</span> {{ $order->user->address }}
                        </div>
                    @endif
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
                        @if($order->order_status === 'ready_for_pickup')
                            <form action="{{ route('delivery.mark-out-for-delivery', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-bold">
                                    Start Delivery
                                </button>
                            </form>
                        @elseif($order->order_status === 'out_for_delivery')
                            <form action="{{ route('delivery.mark-delivered', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-bold">
                                    Mark Delivered
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8 bg-white rounded-lg">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p>No assigned orders</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
    Echo.channel('delivery.orders')
        .listen('.order.status.updated', (e) => {
            if (e.order.order_status === 'ready_for_pickup') {
                playNotificationSound();
            }
            location.reload();
        });
</script>
@endpush

