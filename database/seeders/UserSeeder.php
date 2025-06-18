<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 clientes
        User::factory(10)->cliente()->create()->each(function ($user) {
            $user->assignRole('cliente');
        });

        // Crear 5 profesionales
        User::factory(5)->profesional()->create()->each(function ($user) {
            $user->assignRole('profesional');
        });
    }
}
