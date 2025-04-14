<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoomsHistory extends Model
{
	use HasFactory;

	protected $fillable = [
		'game_room_id',
		'game_data',
	];
}
