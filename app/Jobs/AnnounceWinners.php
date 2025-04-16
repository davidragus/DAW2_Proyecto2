<?php

namespace App\Jobs;

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
			'line' => ($totalChips * $percentages['line']) / count($winners['line']),
			'bingo' => ($totalChips * $percentages['bingo']) / count($winners['bingo']),
		];

		foreach ($winners as $type => $ids) {
			User::whereIn('id', $ids)->increment('chips', $distributedChips[$type]);
		}

		GameRoomsPlayer::where('game_room_id', $gameRoomId)->delete();
		GameRoom::where('id', $gameRoomId)->update(['status' => 'WAITING']);

		broadcast(new BroadcastWinners($winners, $gameRoomId));
	}
}
