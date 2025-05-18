<?php

namespace App\Http\Controllers;

use App\Models\PendingRegistration;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\DataActivities;
use App\Models\DataField;
use App\Models\DataPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    function dashboard()
    {

        // Menggunakan guard 'api' untuk mengambil user
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Ambil fields beserta relasi plants dan activities untuk user terautentikasi

        // Ambil fields dengan relasi plants dan kegiatan (plant-level)
        $fields = DataField::with(['plants.activities'])->where('user_id', $user->id)->get();
        $plantIds = $fields->pluck('plants.*.id')->flatten()->unique()->toArray();


        // Gunakan dd() untuk melihat hasilnya tanpa reindex:
        $reduce = $fields->reduce(function ($carry, $field) {
            $id = $field->plants->pluck('id')->all();
            $aaa = [];
            foreach ($id as $key => $value) {
                $aaa[$value] = $field->field_name;
            }
            return $carry + $aaa;
        }, []);
        // dd($id);
        // Mengambil seluruh tanaman dari semua field dan mengelompokkan berdasarkan nama
        $plantPanenCounts = $fields->flatMap(function ($field) {
            return $field->plants;
        })->groupBy('name')->map(function ($plants) {
            // Untuk setiap grup tanaman, hitung total aktivitas "Panen"
            return $plants->sum(function ($plant) {
                return $plant->activities->where('activity_type', 'Panen')->count();
            });
        })->all();

        // Siapkan data untuk diagram pie
        $plant["labels"] = array_keys($plantPanenCounts);   // Nama-nama tanaman
        $plant['counts'] = array_values($plantPanenCounts);     // Jumlah "Panen" per tanaman


        $plants = DataPlant::whereIn('id', $plantIds)->get()->keyBy('id');

        // Ambil aktivitas berdasarkan plant IDs, dan grup berdasarkan data_plant_id, activity_date, dan activity_type
        $activities = DataActivities::selectRaw('data_plant_id, activity_date, activity_type, COUNT(*) as total')
            ->whereIn('data_plant_id', $plantIds)
            ->groupBy('data_plant_id', 'activity_date', 'activity_type')
            ->orderBy('activity_date')
            ->get();

        // Buat array detail untuk tooltip: kunci berupa "activity_date|activity_type" dan nilainya list plant detail
        $details = [];
        foreach ($activities as $act) {
            $key = $act->activity_date . '|' . $act->activity_type;
            $plantName = isset($plants[$act->data_plant_id])
                ? $reduce[$act->data_plant_id] . " -> " . $plants[$act->data_plant_id]->name . " (" . $plants[$act->data_plant_id]->variety . ")"
                : 'Unknown';
            // Misalnya, kita ingin menampilkan nama tanaman dan total aktivitas untuk kombinasi tersebut
            $details[$key][] = $plantName . " [" . $act->total . "]";
        }

        // Untuk keperluan grafik stacked, kita mungkin ingin mengagregasi per tanggal dan tipe aktivitas (tanpa memecah berdasarkan plant)
        // Jika begitu, kita bisa membuat agregasi berikut:
        $aggregated = [];
        foreach ($activities as $act) {
            $key = $act->activity_date . '|' . $act->activity_type;
            if (!isset($aggregated[$key])) {
                $aggregated[$key] = [
                    'activity_date' => $act->activity_date,
                    'activity_type' => $act->activity_type,
                    'total' => 0,
                ];
            }
            $aggregated[$key]['total'] += $act->total;
        }

        // Dapatkan semua tanggal (union dari seluruh activity_date)
        $datesSet = [];
        foreach ($aggregated as $item) {
            $datesSet[] = $item['activity_date'];
        }
        $dates = array_unique($datesSet);
        sort($dates);

        // Dapatkan semua jenis aktivitas yang muncul
        $activityTypes = collect($aggregated)->pluck('activity_type')->unique()->values()->all();

        // Buat dataset untuk tiap activity type: untuk setiap tanggal, ambil count (jika tidak ada, isi 0)
        $datasets = [];
        foreach ($activityTypes as $type) {
            $dataArr = [];
            foreach ($dates as $date) {
                $key = $date . '|' . $type;
                $dataArr[] = isset($aggregated[$key]) ? $aggregated[$key]['total'] : 0;
            }
            $datasets[] = [
                'label' => $type,
                'data' => $dataArr,
                'backgroundColor' => 'rgba(59,130,246,0.6)',  // Warna default, bisa diubah di front end
                'borderColor' => 'rgba(59,130,246,1)',
                'borderWidth' => 1,
            ];
        }


        //////////////////////////////////////

        $local = Carbon::now("UTC")->addDays(2);
        // Ambil data cuaca real-time dari BMKG
        // Misalnya, BMKG menyediakan data dalam format XML di URL tertentu untuk kota Malang.
        $bmkgUrl = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=35.07.19.2001&utc_datetime=" . $local;
        $bmkgResponse = Http::get($bmkgUrl);
        $weatherData = null;

        if ($bmkgResponse->successful()) {
            // Jika response berupa JSON, langsung decode menggunakan json()
            $weatherData = $bmkgResponse->json();
        }


        // dd($bmkgUrl);


        return response()->json([
            'fields'      => $fields,
            'plants'      => $plant,
            'weatherData' => $weatherData,
            'dates' => $dates,
            'datasets' => $datasets,
            'details' => $details, // Data detail untuk tooltip (kunci: "activity_date|activity_type")


        ]);
    }


    /**
     * GET /api/users
     * Ambil semua user (bisa ditambahkan pagination atau filter).
     */
    public function index()
    {
        // Contoh: hanya admin yang boleh melihat daftar user
        // $this->authorize('isAdmin');

        // Ambil semua user (sesuaikan dengan kebutuhan)
        $users = User::all();

        return response()->json($users, 200);
    }

    /**
     * GET /api/users/{id}
     * Tampilkan satu data user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * POST /api/users
     * Tambah user baru.
     * Password bisa di-generate acak, atau dari input user.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|string|max:50', // Sesuaikan dengan kolom role Anda
            'address'  => 'nullable|string|max:255',
            'contact'  => 'nullable|string|max:255',
            // Jika ingin user memasukkan password langsung, tambahkan:
            // 'password' => 'required|min:6',
        ]);

        // Jika password tidak diminta dari form, bisa kita set default atau random
        $password = $request->input('password') ?? 'password123'; // ganti sesuai kebutuhan

        // Buat user baru
        $user = new User();
        $user->name    = $validated['name'];
        $user->email   = $validated['email'];
        $user->role    = $validated['role'];
        $user->address = $validated['address'] ?? null;
        $user->contact = $validated['contact'] ?? null;
        $user->password = Hash::make($password);
        $user->save();

        return response()->json($user, 201);
    }

    /**
     * PUT/PATCH /api/users/{id}
     * Update data user, kecuali password.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data update
        // Email harus unique, tapi boleh sama dengan user saat ini
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role'     => 'required|string|max:50',
            'address'  => 'nullable|string|max:255',
            'contact'  => 'nullable|string|max:255',
            // Tidak ada password di sini, password hanya bisa direset via resetPassword()
        ]);

        $user->update($validated);

        return response()->json($user, 200);
    }

    /**
     * DELETE /api/users/{id}
     * Hapus user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);
    }

    /**
     * POST /api/users/{id}/reset-password
     * Reset password user ke default atau random.
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Misalnya kita set password default "password123"
        // atau bisa juga generate random:
        // $newPassword = Str::random(8);
        $newPassword = '123';

        $user->password = Hash::make($newPassword);
        $user->save();

        // Kembalikan respon, boleh menampilkan password baru (atau tidak).
        return response()->json([
            'message' => 'Password has been reset.',
            'new_password' => $newPassword
        ], 200);
    }
}
