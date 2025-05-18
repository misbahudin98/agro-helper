<?php
namespace Database\Seeders\Tables;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataFieldsSeeder extends Seeder
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
         * artisan seed:generate --table-mode --tables=data_fields
         *
         */

        $dataTables = [
            [
                'id' => 1,
                'user_id' => 2,
                'field_name' => 'sector-1',
                'location' => ',Keude Bakongan
',
                'bmkg' => '11.01.01.2001',
                'size' => 1.2,
                'description' => 'Deskripsi untuk lahan c',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-16 22:56:42',
            ],
            [
                'id' => 2,
                'user_id' => 4,
                'field_name' => 'field-a',
                'location' => 'Desa Permanu',
                'bmkg' => '35.07.19.2001',
                'size' => 1,
                'description' => 'Deskripsi untuk lahan a',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-13 12:41:10',
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'field_name' => 'sector-2',
                'location' => 'JAWA TIMUR | KAB. MALANG | Pakisaji | Karangpandan',
                'bmkg' => '35.07.19.2002',
                'size' => 1,
                'description' => 'Deskripsi untuk lahan a',
                'created_at' => '2025-03-13 12:41:10',
                'updated_at' => '2025-03-28 18:13:44',
            ],
            [
                'id' => 5,
                'user_id' => 2,
                'field_name' => 'sector-12',
                'location' => 'JAWA TIMUR | KAB. MALANG | Pakisaji | Permanu',
                'bmkg' => '35.07.19.2001',
                'size' => 1.2,
                'description' => 1,
                'created_at' => '2025-03-27 10:21:20',
                'updated_at' => '2025-03-27 10:29:53',
            ]
        ];
        
        DB::table("data_fields")->insert($dataTables);
    }
}