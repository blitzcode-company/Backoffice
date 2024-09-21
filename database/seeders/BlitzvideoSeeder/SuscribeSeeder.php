<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuscribeSeeder extends Seeder
{
    public function run()
    {
        $connection = DB::connection('blitzvideo');

        for ($userId = 2; $userId <= 8; $userId++) {
            for ($canalId = 2; $canalId <= 8; $canalId++) {
                $connection->table('suscribe')->insert([
                    'user_id' => $userId,
                    'canal_id' => $canalId,
                ]);
            }
        }
    }
}
