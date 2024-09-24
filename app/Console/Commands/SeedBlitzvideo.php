<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeedBlitzvideo extends Command
{
    protected $signature = 'db:seed-blitzvideo';
    protected $description = 'Run seeders from the seeders-blitzvideo folder in order';

    public function handle()
    {
        // Ruta a la carpeta donde están los seeders
        $seedersPath = database_path('seeders/BlitzvideoSeeder');

        // Obtener y ordenar los archivos de seeder por su nombre
        $seederFiles = collect(File::files($seedersPath))
            ->sortBy(function ($file) {
                return $file->getFilename();
            });

        foreach ($seederFiles as $file) {
            // Obtener el nombre del archivo de seeder sin extensión
            $seederClass = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            $this->info("Ejecutando seeder: $seederClass");

            // Ejecutar el seeder
            $exitCode = Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true, // Forzar el seeder si es necesario
            ]);

            // Verificar si hubo un error en la ejecución del seeder
            if ($exitCode !== 0) {
                $this->error("Error al ejecutar el seeder: $seederClass");
                break; // Romper el ciclo si hay un error
            }
        }

        $this->info('Todos los seeders de Blitzvideo han sido ejecutados.');
    }
}
