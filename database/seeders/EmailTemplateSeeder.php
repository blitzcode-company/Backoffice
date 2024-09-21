<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $filePath = database_path('csv/correo_plantilla.csv');

        if (!File::exists($filePath)) {
            return;
        }

        $csvData = array_map('str_getcsv', file($filePath));
        array_shift($csvData);

        foreach ($csvData as $row) {
            DB::table('email_templates')->insert([
                'clave_plantilla' => $row[0],
                'asunto' => $row[1],
                'cuerpo' => str_replace('\n', "\n", $row[2]),
                'updated_at' => now(),
            ]);
        }
    }
}
