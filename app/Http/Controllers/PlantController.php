<?php

namespace App\Http\Controllers;

use App\Models\DataPlant;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    /**
     * GET /plants
     * Ambil semua data plants milik user yang sedang login
     */
    public function index()
    {
  
        $userId =  Auth::user()->id;

        // Eager load relasi 'field' agar direspons 
        // ikut menampilkan data field (jika diinginkan)
        $plants = DataPlant::with('DataField')
            ->whereHas('DataField', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return response()->json($plants);
    }

    /**
     * GET /plants/{id}
     * Tampilkan satu data plant
     */
    public function show($id)
    {
        $plant = DataPlant::with('field')->findOrFail($id);

       

        return response()->json($plant);
    }

    /**
     * POST /plants
     * Tambah data plant baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'data_field_id'           => 'required|exists:data_fields,id',
                'name'                    => 'required|string',
                'variety'                 => 'required|string',
                'planting_date'           => 'required|date',
                'expected_harvest_date'   => 'required|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        // Tambahkan user_id agar plant terhubung ke user
        $validated['user_id'] = Auth::id();

        $plant = DataPlant::create($validated);

        return response()->json($plant, 201);
    }

    /**
     * PUT /plants/{id}
     * Update data plant
     */
    public function update(Request $request, $id)
    {
        $plant = DataPlant::findOrFail($id);

    

        try {
            $validated = $request->validate([
                'data_field_id'           => 'required|exists:data_fields,id',
                'name'                    => 'required|string',
                'variety'                 => 'required|string',
                'planting_date'           => 'required|date',
                'expected_harvest_date'   => 'required|date',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        $plant->update($validated);

        return response()->json($plant);
    }

    /**
     * DELETE /plants/{id}
     * Hapus data plant
     */
    public function destroy($id)
    {
        $plant = DataPlant::findOrFail($id);

   
        $plant->delete();
        return response()->json(['message' => 'Plant deleted successfully']);
    }
}
