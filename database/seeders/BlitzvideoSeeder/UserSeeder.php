<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use App\Models\Blitzvideo\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $names = ['Diego', 'Kevin', 'Mateo', 'Sophia', 'William', 'Olivia', 'James', 'Ava', 'Alexander', 'Isabella'];

        foreach ($names as $name) {
            $email = strtolower($name) . '@gmail.com';

            User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'premium' => rand(0, 1) ? true : false,
                'foto' => null,
                'remember_token' => null,
            ]);
        }
    }
}