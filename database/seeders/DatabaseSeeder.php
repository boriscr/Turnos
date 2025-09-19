<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SettingSeeder::class,
        ]);
        User::factory()->create([
            'name' => 'Administrador',
            'surname' => 'Sistema',
            'idNumber' => '12345678',
            'birthdate' => '1990-01-01',
            'gender' => 'Otro',
            'country' => 'Argentina',
            'province' => 'Buenos Aires',
            'city' => 'La Plata',
            'address' => 'Calle Falsa 123',
            'phone' => '2211234567',
            'email' => 'admin@admin.com',
            'password' => bcrypt('Clavesegura123'),
        ])->assignRole('admin');
    }
}
