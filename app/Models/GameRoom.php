<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
	use HasFactory;

	protected $fillable = [
		'game_id', 
		'name',
		'max_users',
		'status',
	];
}
