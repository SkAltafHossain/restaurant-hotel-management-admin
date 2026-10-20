<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Category;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('category')->orderBy('room_number')->paginate(20);
        $categories = Category::where('type', 'room')->get();

        return view('admin.rooms.index', compact('rooms', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'room')->get();

        return view('admin.rooms.create', compact('categories'));
    }

    public function store(RoomRequest $request)
    {
        Room::create($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load('category', 'roomBookings');

        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $categories = Category::where('type', 'room')->get();

        return view('admin.rooms.edit', compact('room', 'categories'));
    }

    public function update(RoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
