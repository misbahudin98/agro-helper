<?php

namespace App\Http\Controllers;

use App\Models\DataField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil user yang sedang login.
     */
    public function edit(Request $request)
    {
        // Ambil data user yang login
        $user = $request->user();
        // Tampilkan view 'profile.edit' dengan data user
        return $user;
    }

    /**
     * Menerima data dari form untuk update profil user yang sedang login.
     */
    public function update(Request $request)
    {
        $user = $request->user(); // atau Auth::user()

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'nullable|min:8|confirmed',
                "password_current" => ["required", "current_password"],

                'email' => [
                    'required',
                    'email',
                    // Email harus unique, tapi boleh sama dengan email user saat ini
                    Rule::unique('users')->ignore($user->id),
                ],
                'address' => 'nullable|string',
                'contact' => 'nullable|string',
                // Jika ingin update password, tambahkan rules di sini
                // 'password' => 'nullable|string|min:6|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        
        }

        // Update data user
        $user->update($validated);

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
