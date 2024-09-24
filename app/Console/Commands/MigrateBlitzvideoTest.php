<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class MigrateBlitzvideoTest extends Command
{
    protected $signature = 'migrate:blitzvideo-test';
    protected $description = 'Run migrations from the migrations-blitzvideo-test folder in order, one by one';

    public function handle()
    {
        $migrationsPath = database_path('migrations-blitzvideo-test');
        $migrationFiles = collect(File::files($migrationsPath))
            ->sortBy(function ($file) {
                return $file->getFilename(); 
            });

        foreach ($migrationFiles as $file) {
            $migration = $file->getFilename();
            $this->info("Ejecutando migración: $migration");
            $migrationNameWithoutExtension = pathinfo($migration, PATHINFO_FILENAME);
            if ($migrationNameWithoutExtension === '2014_10_12_000000_create_users_table' && Schema::connection('blitzvideo')->hasTable('users')) {
                $this->info("La tabla 'users' ya existe. Saltando migración.");
                continue;
            }
            $exitCode = Artisan::call('migrate', [
                '--path' => "database/migrations-blitzvideo-test/$migration",
                '--database' => 'blitzvideo',
                '--force' => true,
            ]);
            if ($exitCode !== 0) {
                $this->error("Error al ejecutar la migración: $migration");
                break;
            }
        }

        $this->info('Todas las migraciones de blitzvideo-test han sido ejecutadas en orden.');
    }
}
