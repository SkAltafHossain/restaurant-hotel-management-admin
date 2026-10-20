<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Models\Salary;
use App\Models\User;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('user', 'createdBy')->orderBy('payment_date', 'desc')->paginate(20);
        $users = User::where('status', 'active')->get();

        return view('admin.salaries.index', compact('salaries', 'users'));
    }

    public function store(SalaryRequest $request)
    {
        $data = $request->validated();
        $data['net_salary'] = $data['basic_salary'] + ($data['allowances'] ?? 0) + ($data['bonus'] ?? 0) - ($data['deductions'] ?? 0);
        $data['created_by'] = auth()->id();

        Salary::create($data);

        return redirect()->route('admin.salaries.index')->with('success', 'Salary payment recorded successfully.');
    }

    public function show(Salary $salary)
    {
        $salary->load('user', 'createdBy');

        return view('admin.salaries.show', compact('salary'));
    }

    public function update(SalaryRequest $request, Salary $salary)
    {
        $data = $request->validated();
        $data['net_salary'] = $data['basic_salary'] + ($data['allowances'] ?? 0) + ($data['bonus'] ?? 0) - ($data['deductions'] ?? 0);

        $salary->update($data);

        return redirect()->route('admin.salaries.index')->with('success', 'Salary payment updated successfully.');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();

        return redirect()->route('admin.salaries.index')->with('success', 'Salary payment deleted successfully.');
    }
}
