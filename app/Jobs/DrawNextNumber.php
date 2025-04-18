<?php

namespace App\Jobs;

use App\Models\GameRoom;
use App\Models\GameRoomsPlayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\DrawNumber;
use App\Models\GameRoomsHistory;

class DrawNextNumber implements ShouldQueue
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
		$history = GameRoomsHistory::find($this->historyId);
		$gameData = json_decode($history->game_data, true);

		if ($gameData['winners']['bingo']) {
			AnnounceWinners::dispatch($this->historyId)->onQueue('bingo');
			return;
		}

		$alreadyDrawn = $gameData['numbers'] ?? [];
		$remaining = array_values(array_diff(range(1, 90), $alreadyDrawn));

		if (empty($remaining)) {
			DrawNextNumber::dispatch($this->historyId)->onQueue('bingo')->delay(now()->addSeconds(5));
			return;
		}

		$number = $remaining[array_rand($remaining)];
		$gameData['numbers'][] = $number;

		$history->game_data = json_encode($gameData);
		$history->save();

		broadcast(new DrawNumber($number, $history->game_room_id));

		DrawNextNumber::dispatch($this->historyId)->onQueue('bingo')->delay(now()->addSeconds(5));
	}
}
