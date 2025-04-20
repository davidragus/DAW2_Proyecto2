<?php

namespace App\Jobs;

use App\Models\GameRoomsPlayersHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GameRoom;
use App\Models\GameRoomsPlayer;
use App\Events\BroadcastWinners;
use App\Models\User;
use App\Models\GameRoomsHistory;

class AnnounceWinners implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $historyId;

	/**
	 * Create a new job instance.
	 */
	public function __construct($historyId)
	{
		$this->historyId = $historyId;
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$gameRoomHistory = GameRoomsHistory::find($this->historyId);
		$gameRoomId = $gameRoomHistory->game_room_id;
		$winners = json_decode($gameRoomHistory->game_data, true)['winners'];
		$players = GameRoomsPlayer::where('game_room_id', $gameRoomId)->get();

		$percentages = [
			'line' => 0.3,
			'bingo' => 0.6,
		];
		$totalChips = 0;

		foreach ($players as $player) {
			$totalChips += $player->chips_betted;
		}

		$distributedChips = [
			'line' => floor(($totalChips * $percentages['line']) / count($winners['line'])),
			'bingo' => floor(($totalChips * $percentages['bingo']) / count($winners['bingo'])),
		];

		$playerHistory = [];
		foreach ($players as $player) {
			$winnings = 0;
			if (in_array($player->user_id, $winners['line'])) {
				$winnings += $distributedChips['line'];
			}
			if (in_array($player->user_id, $winners['bingo'])) {
				$winnings += $distributedChips['bingo'];
			}

			$playerHistory[$player->user_id] = [
				'game_room_id' => $gameRoomId,
				'user_id' => $player->user_id,
				'bet_amount' => $player->chips_betted,
				'win_amount' => $winnings,
				'result' => in_array($player->user_id, $winners['line']) || in_array($player->user_id, $winners['bingo']) ? 'WIN' : 'LOSE',
				'created_at' => now(),
				'updated_at' => now(),
			];
		}

		GameRoomsPlayersHistory::insert($playerHistory);

		foreach ($winners as $type => $ids) {
			User::whereIn('id', $ids)->increment('chips', $distributedChips[$type]);
		}

		GiveAchievements::dispatch($gameRoomId, $players->pluck('user_id')->toArray());

		GameRoomsPlayer::where('game_room_id', $gameRoomId)->delete();
		GameRoom::where('id', $gameRoomId)->update(['status' => 'WAITING']);

		broadcast(new BroadcastWinners($winners, $gameRoomId));
	}
}
