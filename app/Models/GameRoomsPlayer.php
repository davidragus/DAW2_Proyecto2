<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoomsPlayer extends Model
{
	use HasFactory;

	protected $fillable = [
		'game_room_id',
		'user_id',
		'is_ready',
		'game_data',
	];
}
