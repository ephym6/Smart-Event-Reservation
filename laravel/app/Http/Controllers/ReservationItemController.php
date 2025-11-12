<?php

namespace App\Http\Controllers;

use App\Models\ReservationItem;
use Illuminate\Http\Request;

class ReservationItemController extends Controller
{
    public function index()
    {
        return response()->json(ReservationItem::with(['reservation', 'item'])->get());
    }

    public function show($id)
    {
        $resItem = ReservationItem::with(['reservation', 'item'])->findOrFail($id);
        return response()->json($resItem);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required|exists:reservations,reservation_id',
            'item_id' => 'required|exists:inventory_items,item_id',
            'quantity_reserved' => 'nullable|integer|min:1',
        ]);

        $resItem = ReservationItem::create($data);
        return response()->json($resItem, 201);
    }

    public function update(Request $request, $id)
    {
        $resItem = ReservationItem::findOrFail($id);
        $resItem->update($request->all());
        return response()->json($resItem);
    }

    public function destroy($id)
    {
        ReservationItem::destroy($id);
        return response()->json(['message' => 'Reservation item deleted']);
    }
}
