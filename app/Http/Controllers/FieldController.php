<?php

namespace App\Http\Controllers;

use App\Models\DataField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FieldController extends Controller
{
    // GET /items : Get all items
    public function index()
    {
        $id =  Auth::user()->id;

        return response()->json(DataField::where("user_id", $id)->get());
    }

    // GET /api/items/{id}
    public function show($id)
    {
        return response()->json(DataField::findOrFail($id));
    }

    // POST /items : Create a new item
    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'field_name' => 'required|string',
                'location'   => 'required|string',
                'bmkg'       => 'required|string',
                'size'       => 'required|decimal:1',
                'description' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        $validated['user_id'] =  Auth::user()->id;
        $item = DataField::create($validated);
        return response()->json($item, 201);
    }

    // PUT /items/{id} : Update an existing item
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'field_name' => 'required|string',
                'location'   => 'required|string',
                'bmkg'       => 'required|string',
                'size'       => 'required|decimal:1',
                'description' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }


        $item = DataField::findOrFail($id);
        $item->update($validated);
        return response()->json($item);
    }

    // DELETE /items/{id} : Delete an item
    public function destroy($id)
    {
        $item = DataField::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
