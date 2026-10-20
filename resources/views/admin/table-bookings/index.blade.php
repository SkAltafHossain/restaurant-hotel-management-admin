@extends('admin.layouts.app')
@section('title', 'Table Bookings')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Table Bookings</h1>
            <p class="mt-1 text-sm text-gray-500">Manage restaurant table reservations</p>
        </div>
    </div>

    <!-- Table Bookings Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">All Table Bookings</h2>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guests</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($bookings as $booking)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->user->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->table_number }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->booking_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->time_slot }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->guest_count }}</td>
                <td class="px-6 py-4"><span class="px-2 inline-flex text-xs rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($booking->status) }}</span></td>
                <td class="px-6 py-4 text-sm font-medium">
                    <a href="{{ route('admin.table-bookings.edit', $booking) }}" class="text-green-600 hover:text-green-900">Update Status</a>
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
