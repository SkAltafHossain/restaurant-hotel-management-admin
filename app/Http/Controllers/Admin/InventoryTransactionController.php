<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        $transactions = InventoryTransaction::with('inventory', 'performedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.inventory-transactions.index', compact('transactions'));
    }

    public function create()
    {
        $inventories = Inventory::all();
        return view('admin.inventory-transactions.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'transaction_type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $inventory = Inventory::find($request->inventory_id);

        if ($request->transaction_type === 'stock_out' && $inventory->current_stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock for this transaction.');
        }

        $transaction = InventoryTransaction::create([
            'inventory_id' => $request->inventory_id,
            'transaction_type' => $request->transaction_type,
            'quantity' => $request->quantity,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'performed_by' => auth()->id(),
        ]);

        if ($request->transaction_type === 'stock_in') {
            $inventory->increment('current_stock', $request->quantity);
        } else {
            $inventory->decrement('current_stock', $request->quantity);
        }

        return redirect()->route('admin.inventory-transactions.index')
            ->with('success', 'Inventory transaction recorded successfully.');
    }

    public function show(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->load('inventory', 'performedBy');
        return view('admin.inventory-transactions.show', compact('inventoryTransaction'));
    }

    public function edit(string $id)
    {
        return back()->with('error', 'Inventory transactions cannot be edited.');
    }

    public function update(Request $request, string $id)
    {
        return back()->with('error', 'Inventory transactions cannot be edited.');
    }

    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        return back()->with('error', 'Inventory transactions cannot be deleted.');
    }
}
