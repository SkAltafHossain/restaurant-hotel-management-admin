@extends('kitchen.layout')

@section('title', 'Kitchen Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Pending Orders -->
    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-yellow-500">Pending Orders</h2>
            <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-bold">{{ $pendingOrders->count() }}</span>
        </div>
        <div class="space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">
            @forelse($pendingOrders as $order)
                <div class="order-card bg-gray-700 rounded-lg p-4 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-400 ml-2">{{ $order->order_type }}</span>
                        </div>
                        <span class="text-sm text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @if($order->table)
                        <div class="text-sm text-gray-300 mb-2">
                            Table: {{ $order->table->table_number }}
                        </div>
                    @endif
                    <div class="space-y-2 mb-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span>{{ $item->quantity }}x {{ $item->item->name }}</span>
                                <span class="text-gray-400">₹{{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-600">
                        <div class="text-sm">
                            <span class="text-gray-400">Total:</span>
                            <span class="font-bold text-green-400">₹{{ number_format($order->final_amount, 2) }}</span>
                        </div>
                        <form action="{{ route('kitchen.start-preparing', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black rounded font-bold text-sm">
                                Start Preparing
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No pending orders</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Preparing Orders -->
    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-blue-500">Preparing</h2>
            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">{{ $preparingOrders->count() }}</span>
        </div>
        <div class="space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">
            @forelse($preparingOrders as $order)
                <div class="order-card bg-gray-700 rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-400 ml-2">{{ $order->order_type }}</span>
                        </div>
                        <span class="text-sm text-gray-400">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @if($order->table)
                        <div class="text-sm text-gray-300 mb-2">
                            Table: {{ $order->table->table_number }}
                        </div>
                    @endif
                    <div class="space-y-2 mb-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span>{{ $item->quantity }}x {{ $item->item->name }}</span>
                                <span class="text-gray-400">₹{{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-600">
                        <div class="text-sm">
                            <span class="text-gray-400">Total:</span>
                            <span class="font-bold text-green-400">₹{{ number_format($order->final_amount, 2) }}</span>
                        </div>
                        <form action="{{ route('kitchen.mark-ready', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded font-bold text-sm">
                                Mark Ready
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <p>No orders being prepared</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Ready Orders -->
    <div class="bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-green-500">Ready</h2>
            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">{{ $readyOrders->count() }}</span>
        </div>
        <div class="space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">
            @forelse($readyOrders as $order)
                <div class="order-card bg-gray-700 rounded-lg p-4 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-bold">#{{ $order->id }}</span>
                            <span class="text-sm text-gray-400 ml-2">{{ $order->order_type }}</span>
                        </div>
                        <span class="text-sm font-bold text-green-400">{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</span>
                    </div>
                    @if($order->table)
                        <div class="text-sm text-gray-300 mb-2">
                            Table: {{ $order->table->table_number }}
                        </div>
                    @endif
                    <div class="space-y-2 mb-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between text-sm">
                                <span>{{ $item->quantity }}x {{ $item->item->name }}</span>
                                <span class="text-gray-400">₹{{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-600">
                        <div class="text-sm">
                            <span class="text-gray-400">Total:</span>
                            <span class="font-bold text-green-400">₹{{ number_format($order->final_amount, 2) }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $order->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No ready orders</p>
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

    // Listen for new orders
    Echo.channel('kitchen.orders')
        .listen('.order.placed', (e) => {
            playNotificationSound();
            location.reload();
        })
        .listen('.order.status.updated', (e) => {
            location.reload();
        });
</script>
@endpush

