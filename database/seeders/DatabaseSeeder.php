<?php

namespace Database\Seeders;

// Namespace correcto — App\Domains\Auth\Models\User, no App\Models\User
use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles primero — assignRole() falla si el rol no existe
        $this->call(RoleSeeder::class);

        // 2. Usuarios de prueba solo en local
        if (app()->environment('local')) {
            User::firstOrCreate(
                ['email' => 'admin@gotoeat.test'],
                ['name' => 'Admin GoToEat', 'password' => 'password']
            )->assignRole('admin');

            User::firstOrCreate(
                ['email' => 'cliente@gotoeat.test'],
                ['name' => 'Cliente Test', 'password' => 'password']
            )->assignRole('cliente');
        }
    }
}
