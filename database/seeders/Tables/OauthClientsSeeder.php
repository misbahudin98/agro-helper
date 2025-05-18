<?php
namespace Database\Seeders\Tables;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsSeeder extends Seeder
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
         * artisan seed:generate --table-mode --tables=oauth_clients
         *
         */

        $dataTables = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'CLIENT',
                'secret' => NULL,
                'provider' => NULL,
                'redirect' => 'https://misbah.infy.uk/callback.php',
                'personal_access_client' => 0,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2025-03-10 06:09:45',
                'updated_at' => '2025-03-10 06:09:45',
            ]
        ];
        
        DB::table("oauth_clients")->insert($dataTables);
    }
}