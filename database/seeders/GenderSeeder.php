<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('genders')->insert([
            ['name' => 'Male', 'status' => true],
            ['name' => 'Female', 'status' => true],
            ['name' => 'Non-binary', 'status' => true],
            ['name' => 'Prefer not to say', 'status' => false],
            ['name' => 'X', 'status' => true],
            ['name' => 'Other', 'status' => true],
        ]);
    }
}
