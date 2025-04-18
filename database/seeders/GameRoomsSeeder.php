<?php

namespace Database\Seeders;

use App\Models\GameRoom;
use App\Models\Game;
use Illuminate\Database\Seeder;
use Exception; // Add this import

class GameRoomsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$bingoGame = Game::where('name', 'Bingo')->first();

		GameRoom::create([
			'name' => 'Bingo Room 1',
			'game_id' => $bingoGame->id,
		]);
	}
}