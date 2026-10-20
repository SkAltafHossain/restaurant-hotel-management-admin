<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableBookingRequest;
use App\Models\TableBooking;
use Illuminate\Http\Request;

class TableBookingController extends Controller
{
    public function index()
    {
        $bookings = TableBooking::with('user')->orderBy('booking_date', 'desc')->paginate(20);
        return view('admin.table-bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.table-bookings.create');
    }

    public function store(TableBookingRequest $request)
    {
        TableBooking::create($request->validated());
        return redirect()->route('admin.table-bookings.index')->with('success', 'Table booking created successfully.');
    }

    public function show(TableBooking $tableBooking)
    {
        $tableBooking->load('user');
        return view('admin.table-bookings.show', compact('tableBooking'));
    }

    public function edit(TableBooking $tableBooking)
    {
        return view('admin.table-bookings.edit', compact('tableBooking'));
    }

    public function update(Request $request, TableBooking $tableBooking)
    {
        $tableBooking->update($request->only(['status']));
        return redirect()->route('admin.table-bookings.index')->with('success', 'Table booking updated successfully.');
    }

    public function destroy(TableBooking $tableBooking)
    {
        $tableBooking->delete();
        return redirect()->route('admin.table-bookings.index')->with('success', 'Table booking deleted successfully.');
    }
}
