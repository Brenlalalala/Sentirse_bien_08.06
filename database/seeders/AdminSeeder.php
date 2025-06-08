<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@spa.com'],
            [
                'name' => 'Dra. Ana Felicidad',
                'password' => Hash::make('admin123'),
                'es_admin' => true,
                'role' => 'admin',
            ]
        );

        // Asegurarse de que tenga el rol Spatie asignado
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
