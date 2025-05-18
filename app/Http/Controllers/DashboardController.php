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

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil user dari guard 'api'
        $user = auth('api')->user();

        // Fungsi 1: Mengambil data fields, plants, dan informasi terkait
        $fieldPlantData = $this->getFieldPlantData();

        // Fungsi 2: Mengambil data aktivitas untuk chart
        $activityData = $this->getActivityChartDataInternal([],
            $fieldPlantData['plantIds'],
            $fieldPlantData['plants'],
            $fieldPlantData['reduce']
        );

        // Fungsi 3: Mengambil data cuaca
        $firstKey = array_key_first($fieldPlantData['bmkgLocations']);
        $weatherData = $this->getWeatherData($firstKey);

        return response()->json([
            'fields'      => $fieldPlantData['fields'],
            'plants'      => $fieldPlantData['plant'],
            'weatherData' => $weatherData,
            'dates'       => $activityData['dates'],
            'datasets'    => $activityData['datasets'],
            'details'     => $activityData['details'],
            "locations" => $fieldPlantData['bmkgLocations'],
                        "firstLocations" => $firstKey,
        ]);
    }

    /**
     * Fungsi untuk mengambil data fields beserta relasi plants dan informasi terkait.
     */
    protected function getFieldPlantData()
    {
                $user = auth('api')->user();
        $fields = DataField::with(['plants.activities'])->where('user_id', $user->id)->get();
        $plantIds = $fields->pluck('plants.*.id')->flatten()->unique()->toArray();

        $reduce = $fields->reduce(function ($carry, $field) {
            $ids = $field->plants->pluck('id')->all();
            $aaa = [];
            foreach ($ids as $key => $value) {
                $aaa[$value] = $field->field_name;
            }
            return $carry + $aaa;
        }, []);

        $plantPanenCounts = $fields->flatMap(function ($field) {
            return $field->plants;
        })->groupBy('name')->map(function ($plants) {
            return $plants->sum(function ($plant) {
                return $plant->activities->where('activity_type', 'Panen')->count();
            });
        })->all();

        $plant["labels"] = array_keys($plantPanenCounts);   // Nama-nama tanaman
        $plant['counts'] = array_values($plantPanenCounts);   // Jumlah "Panen" per tanaman

        $plants = DataPlant::whereIn('id', $plantIds)->get()->keyBy('id');


  // Tambahkan array dengan kombinasi kolom bmkg sebagai key dan location sebagai value
    $bmkgLocations = $fields->pluck('location', 'bmkg')->toArray();

        return compact('fields', 'plant', 'plantIds', 'reduce', 'plants',"bmkgLocations");
    }
    
    public   function getActivityChart(Request $request){
        $dates =$request->validate([
    "start_date" => 'required|date_format:Y-m-d|before_or_equal:today',
    "end_date"   => 'required|date_format:Y-m-d|before_or_equal:today|after:date_start',
]);
    
$indexedDates = array_values($dates);
    
    return    $this->getActivityChartDataInternal($indexedDates);
    }
    

    /**
     * Fungsi untuk mengagregasi data aktivitas (chart, tooltip, dan dataset).
     */
    protected function getActivityChartDataInternal($date = [],$plantIds = [], $plants = [], $reduce = [])
    {
        if(is_array($date) &&  count($date) === 2 && !$plantIds ){
               // Fungsi 1: Mengambil data fields, plants, dan informasi terkait
        $fieldPlantData = $this->getFieldPlantData();
             $plantIds=  $fieldPlantData['plantIds'];
            $plants = $fieldPlantData['plants'];
            $reduce = $fieldPlantData['reduce'];
               $activities = DataActivities::selectRaw('data_plant_id, activity_date, activity_type, COUNT(*) as total')
            ->whereIn('data_plant_id', $plantIds)
            ->whereBetween('activity_date', [$date[0], $date[1]])
            ->groupBy('data_plant_id', 'activity_date', 'activity_type')
            ->orderBy('activity_date')
            ->get();

        }else{
               $activities = DataActivities::selectRaw('data_plant_id, activity_date, activity_type, COUNT(*) as total')
            ->whereIn('data_plant_id', $plantIds)
            ->groupBy('data_plant_id', 'activity_date', 'activity_type')
            ->orderBy('activity_date')
            ->get();
        }
        


        // Buat array detail untuk tooltip
        $details = [];
        foreach ($activities as $act) {
            $key = $act->activity_date . '|' . $act->activity_type;
            $plantName = isset($plants[$act->data_plant_id])
                ? $reduce[$act->data_plant_id] . " -> " . $plants[$act->data_plant_id]->name . " (" . $plants[$act->data_plant_id]->variety . ")"
                : 'Unknown';
            $details[$key][] = $plantName . " [" . $act->total . "]";
        }

        // Agregasi data untuk grafik stacked
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

        // Dapatkan semua tanggal
        $datesSet = [];
        foreach ($aggregated as $item) {
            $datesSet[] = $item['activity_date'];
        }
        $dates = array_unique($datesSet);
        sort($dates);

        // Dapatkan semua jenis aktivitas yang muncul
        $activityTypes = collect($aggregated)->pluck('activity_type')->unique()->values()->all();

        // Buat dataset untuk tiap jenis aktivitas
        $datasets = [];
        foreach ($activityTypes as $type) {
            $dataArr = [];
            foreach ($dates as $date) {
                $key = $date . '|' . $type;
                $dataArr[] = isset($aggregated[$key]) ? $aggregated[$key]['total'] : 0;
            }
            $datasets[] = [
                'label'           => $type,
                'data'            => $dataArr,
                'backgroundColor' => 'rgba(59,130,246,0.6)',
                'borderColor'     => 'rgba(59,130,246,1)',
                'borderWidth'     => 1,
            ];
        }

        return compact('dates', 'datasets', 'details');
    }

    /**
     * Fungsi untuk mengambil data cuaca dari BMKG.
     */
    public function getWeatherData($bmkg)
    {
    
        $local = Carbon::now("UTC")->addDays(2);
        $bmkgUrl = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=".$bmkg."&utc_datetime=" . $local;
        $bmkgResponse = Http::get($bmkgUrl);
        $weatherData = null;

        if ($bmkgResponse->successful()) {
            $weatherData = $bmkgResponse->json();
        }

        return $weatherData;
    }
}
