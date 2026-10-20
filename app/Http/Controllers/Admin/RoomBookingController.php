<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomBookingRequest;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomBookingController extends Controller
{
    public function index()
    {
        $bookings = RoomBooking::with('user', 'room')->orderBy('check_in_date', 'desc')->paginate(20);
        return view('admin.room-bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.room-bookings.create');
    }

    public function store(RoomBookingRequest $request)
    {
        RoomBooking::create($request->validated());
        return redirect()->route('admin.room-bookings.index')->with('success', 'Room booking created successfully.');
    }

    public function show(RoomBooking $roomBooking)
    {
        $roomBooking->load('user', 'room', 'transactions');
        return view('admin.room-bookings.show', compact('roomBooking'));
    }

    public function edit(RoomBooking $roomBooking)
    {
        return view('admin.room-bookings.edit', compact('roomBooking'));
    }

    public function update(Request $request, RoomBooking $roomBooking)
    {
        $roomBooking->update($request->only(['booking_status', 'payment_status']));
        return redirect()->route('admin.room-bookings.index')->with('success', 'Room booking updated successfully.');
    }

    public function destroy(RoomBooking $roomBooking)
    {
        $roomBooking->delete();
        return redirect()->route('admin.room-bookings.index')->with('success', 'Room booking deleted successfully.');
    }
}
