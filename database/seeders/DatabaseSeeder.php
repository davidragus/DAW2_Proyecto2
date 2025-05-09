<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$this->call(PermissionTableSeeder::class);
		$this->call(CountryTableSeeder::class);
		$this->call(CreateAdminUserSeeder::class);
		$this->call(AchievementsSeeder::class);
		$this->call(GamesSeeder::class);
		$this->call(GameRoomsSeeder::class);

		// $this->call(RoleSeeder::class);
		// \App\Models\User::factory(10)->create();

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);
	}
}
