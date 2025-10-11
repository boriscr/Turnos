<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Doctor',
            'surname' => 'Doctor',
            'idNumber' => '00000002',
            'birthdate' => '1990-01-01',
            'gender_id' => 1,
            'country_id' => 11, // Argentina
            'state_id' => 180, // Jujuy
            'city_id' => 1085, // Abra Pampa
            'address' => 'Calle Falsa 123',
            'phone' => '3885000002',
            'email' => 'doctor@turnos.com',
            'password' => bcrypt('clavesegura123'),
        ])->assignRole('doctor');

        Doctor::create([
            'user_id' => 2,
            'name' => 'Doctor',
            'surname' => 'Doctor',
            'idNumber' => '00000002',
            'phone' => '3885000002',
            'email' => 'doctor@turnos.com',
            'specialty_id' => 1,
            'licenseNumber' => '000002',
            'role' => 'Doctor',
            'status' => true,
        ]);
    }
}
