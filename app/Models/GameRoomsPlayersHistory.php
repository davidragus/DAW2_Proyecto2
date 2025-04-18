<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoomsPlayersHistory extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'game_room_id',
		'bet_amount',
		'win_amount',
		'result',
		'created_at',
		'updated_at',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function gameRoom()
	{
		return $this->belongsTo(GameRoom::class, 'game_room_id');
	}
}
