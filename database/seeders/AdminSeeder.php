<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'surname' => 'Sistema',
            'idNumber' => '12345678',
            'birthdate' => '1990-01-01',
            'gender_id' => 5,
            'country_id' => 11, // Argentina
            'state_id' => 180, // Jujuy
            'city_id' => 1085, // Abra Pampa
            'address' => 'Calle Falsa 123',
            'phone' => '0000000001',
            'email' => 'admin@turnos.com',
            'password' => bcrypt('clavesegura123'),
        ])->assignRole('admin');
    }
}
