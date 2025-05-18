<?php

namespace App\Http\Controllers;

use App\Models\DataActivities;
use App\Models\DataActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * GET /activities
     * Ambil semua data activities yang dimiliki user melalui rantai plant->field->user_id
     */
    public function index()
    {
        $userId = Auth::id();

        // Memuat relasi 'DataPlant' agar bisa menampilkan data plant di response
        // Memfilter agar hanya activities milik user yang bersangkutan
        $activities = DataActivities::with('DataPlant')
            ->whereHas('DataPlant', function ($query) use ($userId) {
                $query->whereHas('DataField', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId);
                });
            })
            ->get();

        return response()->json($activities);
    }

    /**
     * GET /activities/{id}
     * Tampilkan satu data activity
     */
    public function show($id)
    {
        // Muat relasi 'DataPlant' dan di dalamnya 'DataField' (jika ingin lihat field juga)
        // atau cukup 'DataPlant' saja.
        $activity = DataActivities::with('DataPlant.DataField')->findOrFail($id);

        // Opsional: cek kepemilikan user
        // Pastikan rantai data_plant -> data_field -> user_id sesuai user login
        if ($activity->DataPlant->DataField->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return response()->json($activity);
    }

    /**
     * POST /activities
     * Tambah data activity baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'data_plant_id'   => 'required|exists:data_plants,id',
                'activity_type'   => 'required|string',
                'activity_date'   => 'required|date',
                'details'         => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        // Opsional: Anda bisa cek apakah plant milik user login
        // sebelum membuat activity.
        // Contoh (jika user_id ada di data_fields):
        /*
        $plant = DataPlant::with('DataField')->findOrFail($validated['data_plant_id']);
        if ($plant->DataField->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        */

        $activity = DataActivities::create($validated);

        return response()->json($activity, 201);
    }

    /**
     * PUT /activities/{id}
     * Update data activity
     */
    public function update(Request $request, $id)
    {
        $activity = DataActivities::with('DataPlant.DataField')->findOrFail($id);

        // Cek kepemilikan user
        if ($activity->DataPlant->DataField->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $validated = $request->validate([
                'data_plant_id'   => 'required|exists:data_plants,id',
                'activity_type'   => 'required|string',
                'activity_date'   => 'required|date',
                'details'         => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        // Opsional: cek juga apakah plant (jika di-update) milik user login
        /*
        $newPlant = DataPlant::with('DataField')->findOrFail($validated['data_plant_id']);
        if ($newPlant->DataField->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        */

        $activity->update($validated);

        return response()->json($activity);
    }

    /**
     * DELETE /activities/{id}
     * Hapus data activity
     */
    public function destroy($id)
    {
        $activity = DataActivities::with('DataPlant.DataField')->findOrFail($id);

        // Cek kepemilikan user
        if ($activity->DataPlant->DataField->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully']);
    }
}
