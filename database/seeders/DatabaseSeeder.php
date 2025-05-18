<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataField;   // Model untuk tabel fields
use App\Models\Plant;       // Model untuk tabel plants
use App\Models\Activity;    // Model untuk tabel activities

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $fieldsData = [
            [
                'user_id'    => 2,
                'field_name' => 'c',
                'location'   => 'Desa Permanu',
                'bmkg'       => '35.07.19.2001',
                'size'       => 1,
                'description'=> 'Deskripsi untuk lahan c',
                'plants'     => [
                    [
                        'name' => 'padi',
                        'variety' => '-',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Penanaman',
                                'activity_date' => '2025-03-21',
                                'details' => 'Aktivitas penanaman padi di lahan c',
                            ],
                            [
                                'activity_type' => 'Panen',
                                'activity_date' => '2025-03-23',
                                'details' => 'Aktivitas panen padi di lahan c',
                            ],
                        ],
                    ],
                    [
                        'name' => 'singkong',
                        'variety' => 'aa',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Penanaman',
                                'activity_date' => '2025-03-21',
                                'details' => 'Aktivitas penanaman singkong di lahan c',
                            ],
                        ],
                    ],
                    [
                        'name' => 'singkong',
                        'variety' => '-',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Panen',
                                'activity_date' => '2025-03-23',
                                'details' => 'Aktivitas panen singkong di lahan c',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'user_id'    => 4,
                'field_name' => 'a',
                'location'   => 'Desa Permanu',
                'bmkg'       => '35.07.19.2001',
                'size'       => 1,
                'description'=> 'Deskripsi untuk lahan a',
                'plants'     => [
                    [
                        'name' => 'jagung',
                        'variety' => '-',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Penanaman',
                                'activity_date' => '2025-03-21',
                                'details' => 'Aktivitas penanaman jagung di lahan a',
                            ],
                        ],
                    ],
                    [
                        'name' => 'jagung',
                        'variety' => 'aa',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Panen',
                                'activity_date' => '2025-03-26',
                                'details' => 'Aktivitas panen jagung di lahan a',
                            ],
                        ],
                    ],
                    [
                        'name' => 'singkong',
                        'variety' => '-',
                        'planting_date' => '2025-03-12',
                        'expected_harvest_date' => '2025-05-12',
                        'activities' => [
                            [
                                'activity_type' => 'Penanaman',
                                'activity_date' => '2025-03-21',
                                'details' => 'Aktivitas penanaman singkong di lahan a',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($fieldsData as $fieldData) {
            // Buat record field (lahan)
            $field = DataField::create([
                'user_id'    => $fieldData['user_id'],
                'field_name' => $fieldData['field_name'],
                'location'   => $fieldData['location'],
                'bmkg'       => $fieldData['bmkg'],
                'size'       => $fieldData['size'],
                'description'=> $fieldData['description'],
            ]);

            // Buat record tanaman dan aktivitasnya
            foreach ($fieldData['plants'] as $plantData) {
                // Simpan kegiatan yang ada di dalam array 'activities' dan hapus dari data plant
                $activitiesData = $plantData['activities'] ?? [];
                unset($plantData['activities']);
                
                // Buat tanaman dan otomatis field_id diisi
                $plant = $field->plants()->create($plantData);
                
                // Buat aktivitas untuk tanaman tersebut, sehingga data_plant_id akan terisi
                foreach ($activitiesData as $activityData) {
                    $plant->activities()->create($activityData);
                }
            }
        }
    }
}
