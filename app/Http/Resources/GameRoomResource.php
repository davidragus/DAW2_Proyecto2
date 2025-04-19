<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Game;

class GameRoomResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'game_id' => $this->game_id,
			'game_name' => Game::find($this->game_id)->name,
			'name' => $this->name,
			'max_players' => $this->max_users,
			'status' => $this->status
		];
	}
}
