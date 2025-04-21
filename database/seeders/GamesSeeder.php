<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Exception; // Add this import

class GamesSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$newGame = Game::create([
			'name' => 'Bingo',
			'route_path' => 'bingo',
			'active' => true,
		]);
		$filePath = public_path('images/bingoGame.webp');
		if (file_exists($filePath)) {
			$newGame->addMedia($filePath)->preservingOriginal()->toMediaCollection('games');
		} else {
			throw new Exception("File not found: $filePath");
		}
		$fileIconPath = public_path('images/bingoIcon.svg');
		if (file_exists($fileIconPath)) {
			$newGame->addMedia($fileIconPath)->preservingOriginal()->toMediaCollection('games_icons');
		} else {
			throw new Exception("File not found: $fileIconPath");
		}
	}
}