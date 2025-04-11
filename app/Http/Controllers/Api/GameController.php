<?php

namespace App\Http\Controllers\Api;

use App\Events\ChangePlayerStatus;
use App\Events\DrawNumber;
use App\Events\SendCountdown;
use App\Http\Resources\GameRoomPlayerResource;
use App\Models\GameRoom;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameRoomResource;
use App\Models\GameRoomsPlayer;
use Exception;
use Illuminate\Http\Request;

class GameController extends Controller
{

	public function getGameRoom($id)
	{
		try {
			$gameRoom = GameRoom::find($id);

			if ($gameRoom) {
				return new GameRoomResource($gameRoom);
			} else {
				return response()->json([
					'message' => 'Game room not found',
				], 404);
			}
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function getPlayer($gameRoomId, $playerId)
	{
		try {
			$player = GameRoomsPlayer::where('game_room_id', $gameRoomId)->where('user_id', $playerId)->first();

			if ($player) {
				return new GameRoomPlayerResource($player);
			} else {
				return response()->json([
					'message' => 'Player not found in the game room',
				], 404);
			}
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function getPlayersStatus($gameRoomId)
	{
		try {
			$players = GameRoomsPlayer::where('game_room_id', $gameRoomId)->get()->pluck('is_ready', 'user_id')->toArray();

			return response()->json($players, 200);
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function joinGame(Request $request, $id)
	{
		try {
			$gameRoom = GameRoom::find($id);

			if ($gameRoom) {
				GameRoomsPlayer::create([
					'game_room_id' => $gameRoom->id,
					'user_id' => $request->user()->id,
					'game_data' => json_encode($request->game_data),
				]);

				return response()->json([
					'message' => 'You have joined the game room successfully',
				], 200);
			} else {
				return response()->json([
					'message' => 'Game room not found',
				], 404);
			}
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function updatePlayerGameData(Request $request, $gameRoomId, $playerId)
	{
		try {
			GameRoomsPlayer::where('game_room_id', $gameRoomId)->where('user_id', $playerId)->update([
				'game_data' => json_encode($request->game_data),
			]);
			return response()->json([
				'message' => 'Player updated successfully',
			], 200);
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function updatePlayerStatus(Request $request, $gameRoomId, $playerId)
	{
		try {
			GameRoomsPlayer::where('game_room_id', $gameRoomId)->where('user_id', $playerId)->update([
				'is_ready' => $request->is_ready ? 1 : 0,
			]);
			broadcast(new ChangePlayerStatus($playerId, $request->is_ready));

			$playersReady = GameRoomsPlayer::where('game_room_id', $gameRoomId)->where('is_ready', 1)->count();
			$gameRoom = GameRoom::find($gameRoomId);

			if ($playersReady >= 1 && $gameRoom->status == 'WAITING') {
				$gameRoom->status = 'IN_PROGRESS';
				$gameRoom->save();
				$this->sendCountdown($gameRoomId);
			}

			return response()->json([
				'message' => 'Player updated successfully',
			], 200);
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function sendCountdown($gameRoomId)
	{
		broadcast(new SendCountdown(30));
	}

	public function startGame($gameRoomId)
	{

		$this->playBingo($gameRoomId);
	}

	public function playBingo($channelId)
	{
		$allNumbers = range(1, 90);

		while (!empty($allNumbers)) {
			$randomNumber = array_rand($allNumbers);
			$number = $allNumbers[$randomNumber];
			unset($allNumbers[$randomNumber]);

			broadcast(new DrawNumber($number, $channelId));

			sleep(5);
		}
	}
}
