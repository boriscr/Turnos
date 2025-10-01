<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Nnjeim\World\Actions\SeedAction;

class WorldSeeder extends Seeder
{
	public function run()
	{
		//php -d memory_limit=512M artisan world:install
		$this->call([
			SeedAction::class,
		]);
	}
}
