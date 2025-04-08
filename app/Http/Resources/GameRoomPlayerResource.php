<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameRoomPlayerResource extends JsonResource
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
			'user_id' => $this->user_id,
			'game_room_id' => $this->game_room_id,
			'is_ready' => $this->is_ready,
			'game_data' => $this->game_data,
		];
	}
}
