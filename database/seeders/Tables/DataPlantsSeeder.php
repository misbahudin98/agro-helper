<?php
namespace Database\Seeders\Tables;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPlantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Command :
         * artisan seed:generate --table-mode --tables=data_plants
         *
         */

        $dataTables = [
            [
                'id' => 1,
                'data_field_id' => 1,
                'name' => 'padi',
                'variety' => 'arab',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 2,
                'data_field_id' => 1,
                'name' => 'singkong',
                'variety' => 'madu',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 3,
                'data_field_id' => 1,
                'name' => 'jagung',
                'variety' => '-',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 4,
                'data_field_id' => 2,
                'name' => 'jagung',
                'variety' => '-',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 5,
                'data_field_id' => 2,
                'name' => 'jagung',
                'variety' => 'aa',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 6,
                'data_field_id' => 2,
                'name' => 'singkong',
                'variety' => '-',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 7,
                'data_field_id' => 3,
                'name' => 'singkong',
                'variety' => '',
                'planting_date' => '2025-03-12',
                'expected_harvest_date' => '2025-05-12',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ]
        ];
        
        DB::table("data_plants")->insert($dataTables);
    }
}