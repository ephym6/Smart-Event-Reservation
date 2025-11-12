<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        return response()->json(InventoryItem::with('venue')->get());
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
        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy($id)
    {
        InventoryItem::destroy($id);
        return response()->json(['message' => 'Item deleted']);
    }
}
