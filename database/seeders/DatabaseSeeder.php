<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\GenderSeeder;

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
            GenderSeeder::class,
            SpecialtySeeder::class,
            AdminSeeder::class,
            DoctorSeeder::class,
            UserSeeder::class,
            WorldSeeder::class,
        ]);
    }
}
