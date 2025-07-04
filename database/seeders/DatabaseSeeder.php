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
            // otros seeders que ya funcionan
        ]);
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Administrador',
            'surname' => 'Sistema',
            'dni' => '12345678',
            'birthdate' => '1990-01-01',
            'genero' => 'Otro',
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
