<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('createdBy')->orderBy('expense_date', 'desc')->paginate(20);
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(ExpenseRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create($data);
        return redirect()->route('admin.expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load('createdBy');
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $data = $request->validated();

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($data);
        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
