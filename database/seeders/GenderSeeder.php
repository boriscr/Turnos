<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('genders')->insert([
            ['name' => 'Masculino', 'status' => true],
            ['name' => 'Femenino', 'status' => true],
            ['name' => 'No binario', 'status' => true],
            ['name' => 'Prefiero no decirlo', 'status' => false],
            ['name' => 'Otro', 'status' => true],
        ]);
    }
}
