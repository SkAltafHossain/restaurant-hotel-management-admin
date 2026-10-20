<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::orderBy('item_name')->paginate(20);
        return view('admin.inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('admin.inventories.create');
    }

    public function store(InventoryRequest $request)
    {
        Inventory::create($request->validated());
        return redirect()->route('admin.inventories.index')->with('success', 'Inventory item created successfully.');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load('inventoryTransactions');
        return view('admin.inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventories.edit', compact('inventory'));
    }

    public function update(InventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->validated());
        return redirect()->route('admin.inventories.index')->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventories.index')->with('success', 'Inventory item deleted successfully.');
    }
}
