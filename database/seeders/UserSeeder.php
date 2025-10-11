<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuario',
            'surname' => 'User',
            'idNumber' => '00000003',
            'birthdate' => '1990-01-01',
            'gender_id' => 2,
            'country_id' => 11, // Argentina
            'state_id' => 180, // Jujuy
            'city_id' => 1085, // Abra Pampa
            'address' => 'Calle Falsa 123',
            'phone' => '3885000003',
            'email' => 'user@turnos.com',
            'password' => bcrypt('clavesegura123'),
        ])->assignRole('user');
    }
}
