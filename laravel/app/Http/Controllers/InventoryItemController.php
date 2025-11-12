<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with('venue')->get();
        
        if (request()->wantsJson()) {
            return response()->json($items);
        }
        
        return view('inventory.index', compact('items'));
    }

    public function show($id)
    {
        $item = InventoryItem::with('venue')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id' => 'nullable|exists:venues,venue_id',
            'item_name' => 'required|string',
            'quantity_available' => 'nullable|integer|min:0',
        ]);

        $item = InventoryItem::create($data);
        
        if (request()->wantsJson()) {
            return response()->json($item, 201);
        }
        
        return redirect()->route('inventory.index')->with('success', 'Item created successfully');
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($item);
        }
        
        return redirect()->route('inventory.index')->with('success', 'Item updated successfully');
    }

    public function destroy($id)
    {
        InventoryItem::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Item deleted']);
        }
        
        return redirect()->route('inventory.index')->with('success', 'Item deleted successfully');
    }
}
