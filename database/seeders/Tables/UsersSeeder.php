<?php
namespace Database\Seeders\Tables;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
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
         * artisan seed:generate --table-mode --tables=users
         *
         */

        $dataTables = [
            [
                'id' => 2,
                'name' => 'misbah',
                'email' => 'minuq498@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$mQ/BbwFGyXk2Ij.XCb/YWeLyCa4BShbN54yT2qNwmGXEVNpYyJKA.',
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => '2025-03-20 21:04:57',
                'address' => 21,
                'role' => 'admin',
                'contact' => 1,
            ],
            [
                'id' => 3,
                'name' => 'min uq',
                'email' => '1@1.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$mQ/BbwFGyXk2Ij.XCb/YWeLyCa4BShbN54yT2qNwmGXEVNpYyJKA.',
                'remember_token' => NULL,
                'created_at' => '2025-03-06 14:27:48',
                'updated_at' => '2025-03-17 16:00:31',
                'address' => 1,
                'role' => '',
                'contact' => 1,
            ],
            [
                'id' => 4,
                'name' => 'misbahudin',
                'email' => 'minuq98@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$mQ/BbwFGyXk2Ij.XCb/YWeLyCa4BShbN54yT2qNwmGXEVNpYyJKA.',
                'remember_token' => NULL,
                'created_at' => '2025-03-12 09:45:12',
                'updated_at' => '2025-03-17 16:02:11',
                'address' => NULL,
                'role' => NULL,
                'contact' => NULL,
            ]
        ];
        
        DB::table("users")->insert($dataTables);
    }
}