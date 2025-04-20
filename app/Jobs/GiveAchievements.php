<?php

namespace App\Jobs;

use App\Models\GameRoomsPlayersHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Achievement;

class GiveAchievements implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $usersId;
	public $gameRoomId;
	/**
	 * Create a new job instance.
	 */
	public function __construct($gameRoomId, $usersId)
	{
		$this->gameRoomId = $gameRoomId;
		$this->usersId = $usersId;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$users = User::whereIn('id', $this->usersId)->get();

		foreach ($users as $user) {
			$gamesCount = $user->gamesHistory()->count();
			$chipsCount = $user->gamesHistory()->sum('win_amount');
			$winCount = $user->gamesHistory()->where('result', 'WIN')->count();
			$pendingAchievements = Achievement::whereNotIn('id', $user->achievements->pluck('id'))->get();

			foreach ($pendingAchievements as $achievement) {
				switch ($achievement->achievement_type) {
					case 'games':
						if ($gamesCount >= $achievement->amount) {
							$user->achievements()->attach($achievement->id, [
								'obtained_date' => now(),
							]);
							$user->chips += $achievement->reward_amount;
							$user->save();
						}
						break;
					case 'chips':
						if ($chipsCount >= $achievement->amount) {
							$user->achievements()->attach($achievement->id, [
								'obtained_date' => now(),
							]);
							$user->chips += $achievement->reward_amount;
							$user->save();
						}
						break;
					case 'wins':
						if ($winCount >= $achievement->amount) {
							$user->achievements()->attach($achievement->id, [
								'obtained_date' => now(),
							]);
							$user->chips += $achievement->reward_amount;
							$user->save();
						}
						break;
				}
			}
		}
	}
}
