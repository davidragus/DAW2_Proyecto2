<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameRoomResource;
use App\Models\GameRoom;
use Illuminate\Http\Request;

class GameRoomController extends Controller
{
    public function index(){
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'game_id', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $gameRooms = GameRoom::when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_game_id'), function ($query) {
                $query->where('game_id', request('search_game_id'));
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('game_id', request('search_global'));
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        return GameRoomResource::collection($gameRooms);
    } 

    public function show($id){
        $gameRoom = GameRoom::find($id);
        if ($gameRoom) {
            return new GameRoomResource($gameRoom);
        } else {
            return response()->json([
                'message' => 'Game room not found',
            ], 404);
        }
    }

    public function store(Request $request){
        $gameRoom = GameRoom::create($request->all());
        return new GameRoomResource($gameRoom);
    }

    public function update(Request $request, $id){
        $gameRoom = GameRoom::find($id);
        if ($gameRoom) {
            $gameRoom->update($request->all());
            return new GameRoomResource($gameRoom);
        } else {
            return response()->json([
                'message' => 'Game room not found',
            ], 404);
        }
    }

    public function destroy($id){
        $gameRoom = GameRoom::find($id);
        if ($gameRoom) {
            $gameRoom->delete();
            return response()->json([
                'message' => 'Game room deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Game room not found',
            ], 404);
        }
    }
}
