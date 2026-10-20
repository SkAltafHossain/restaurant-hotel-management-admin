<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::with('createdBy')->orderBy('date', 'desc')->paginate(20);

        return view('admin.holidays.index', compact('holidays'));
    }

    public function store(HolidayRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        Holiday::create($data);

        return redirect()->route('admin.holidays.index')->with('success', 'Holiday added successfully.');
    }

    public function update(HolidayRequest $request, Holiday $holiday)
    {
        $holiday->update($request->validated());

        return redirect()->route('admin.holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('admin.holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
