<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->orderBy('name')->paginate(20);
        $categories = Category::where('type', 'food')->get();

        return view('admin.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'food')->get();

        return view('admin.items.create', compact('categories'));
    }

    public function store(ItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function show(Item $item)
    {
        $item->load('category', 'orderItems');

        return view('admin.items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::where('type', 'food')->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(ItemRequest $request, Item $item)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
