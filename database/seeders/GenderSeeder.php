<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        Gender::create([
            'name' => 'Female',
            'status' => true,
        ]);
        Gender::create([
            'name' => 'Male',
            'status' => true,
        ]);
        Gender::create([
            'name' => 'Non_binary',
            'status' => false,
        ]);
        Gender::create([
            'name' => 'X',
            'status' => false,
        ]);
        Gender::create([
            'name' => 'Other',
            'status' => true,
        ]);
        Gender::create([
            'name' => 'Prefer_not_to_say',
            'status' => false,
        ]);
    }
}
