<?php

namespace App\Http\Controllers\Api;

use App\Events\ChangePlayerStatus;
use App\Events\DrawNumber;
use App\Events\SendCountdown;
use App\Http\Resources\GameRoomPlayerResource;
use App\Models\GameRoom;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameRoomResource;
use App\Models\GameRoomsHistory;
use App\Models\GameRoomsPlayer;
use Exception;
use Illuminate\Http\Request;
use App\Jobs\DrawNextNumber;
use App\Models\Game;
use App\Http\Resources\GameResource;

class GameController extends Controller
{

	public function index()
	{
		$orderColumn = request('order_column', 'created_at');
		if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
			$orderColumn = 'created_at';
		}
		$orderDirection = request('order_direction', 'desc');
		if (!in_array($orderDirection, ['asc', 'desc'])) {
			$orderDirection = 'desc';
		}
		$games = Game::when(request('search_id'), function ($query) {
			$query->where('id', request('search_id'));
		})
			->when(request('search_title'), function ($query) {
				$query->where('name', 'like', '%' . request('search_title') . '%');
			})
			->when(request('search_global'), function ($query) {
				$query->where(function ($q) {
					$q->where('id', request('search_global'))
						->orWhere('name', 'like', '%' . request('search_global') . '%');
				});
			})
			->orderBy($orderColumn, $orderDirection)
			->paginate(50);

		return GameResource::collection($games);
	}

	public function show($id)
	{
		$game = Game::find($id);

		if ($game) {
			return new GameResource($game);
		} else {
			return response()->json([
				'message' => 'Game not found',
			], 404);
		}
	}

	public function store(Request $request)
	{
		try {
			$game = Game::create($request->all());

			$game->addMedia($request->file('image'))->toMediaCollection('games');

			return new GameResource($game);
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
				'error' => $ex->getMessage(),
			], 500);
		}
	}

	public function update(Request $request, $id)
	{
		try {
			$game = Game::find($id);

			if ($game) {
				$game->update($request->all());

				if ($request->hasFile('image')) {
					$game->clearMediaCollection('games');
					$game->addMedia($request->file('image'))->toMediaCollection('games');
				}

				return new GameResource($game);
			} else {
				return response()->json([
					'message' => 'Game not found',
				], 404);
			}
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

	public function destroy($id)
	{
		try {
			$game = Game::find($id);

			if ($game) {
				$game->delete();
				return response()->json([
					'message' => 'Game deleted successfully',
				], 200);
			} else {
				return response()->json([
					'message' => 'Game not found',
				], 404);
			}
		} catch (Exception $ex) {
			return response()->json([
				'message' => 'An unexpected error has occurred',
			], 500);
		}
	}

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
					'chips_betted' => $request->chips_betted,
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
			GameRoomsPlayer::where('game_room_id', $gameRoomId)->where('user_id', $playerId)->increment('chips_betted', $request->chips_betted, [
				'game_data' => json_encode($request->game_data)
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
		$history = GameRoomsHistory::create([
			'game_room_id' => $gameRoomId,
			'game_data' => json_encode(['winners' => ['line' => null, 'bingo' => null], 'numbers' => []]),
		]);
		DrawNextNumber::dispatch($history->id)->onQueue('bingo');
		// $this->playBingo($gameRoomId, $history->id);
	}

	// public function playBingo($channelId, $historyId)
	// {
	// 	$gameRoomHistory = GameRoomsHistory::find($historyId);
	// 	$allNumbers = range(1, 90);

	// 	while (!empty($allNumbers)) {
	// 		$randomNumber = array_rand($allNumbers);
	// 		$number = $allNumbers[$randomNumber];
	// 		unset($allNumbers[$randomNumber]);

	// 		$gameData = json_decode($gameRoomHistory->game_data, true);
	// 		$gameData['numbers'][] = $number;

	// 		broadcast(new DrawNumber($number, $channelId));
	// 		$gameRoomHistory->game_data = json_encode($gameData);
	// 		$gameRoomHistory->save();

	// 		sleep(5);
	// 	}
	// }

	public function callLine($gameRoomId, $playerId)
	{
		$gameRoomHistory = GameRoomsHistory::where('game_room_id', $gameRoomId)->get()->last();
		$gameData = json_decode($gameRoomHistory->game_data, true);
		$gameData['winners']['line'][] = $playerId;
		$gameRoomHistory->game_data = json_encode($gameData);
		$gameRoomHistory->save();
	}

	public function callBingo($gameRoomId, $playerId)
	{
		$gameRoomHistory = GameRoomsHistory::where('game_room_id', $gameRoomId)->get()->last();
		$gameData = json_decode($gameRoomHistory->game_data, true);
		$gameData['winners']['bingo'][] = $playerId;
		$gameRoomHistory->game_data = json_encode($gameData);
		$gameRoomHistory->save();
	}
}
