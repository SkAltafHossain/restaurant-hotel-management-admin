@extends('admin.layouts.app')
@section('title', 'Room Bookings')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Room Bookings</h1>
            <p class="mt-1 text-sm text-gray-500">Manage hotel room reservations</p>
        </div>
    </div>

    <!-- Room Bookings Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Room Bookings</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($bookings as $booking)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->guest_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->room->room_number }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->check_in_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->check_out_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($booking->total_price, 2) }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $booking->booking_status === 'checked_out' ? 'bg-green-100 text-green-800' : ($booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst(str_replace('_', ' ', $booking->booking_status)) }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.room-bookings.show', $booking) }}" class="text-green-600 hover:text-green-900 mr-3">View</a>
                    <a href="{{ route('admin.room-bookings.edit', $booking) }}" class="text-green-600 hover:text-green-900">Update</a>
                </td>
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
    @endif
@endsection
