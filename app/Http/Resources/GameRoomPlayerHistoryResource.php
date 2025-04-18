<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameRoomPlayerHistoryResource extends JsonResource
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
			'game_room_name' => $this->gameRoom->name,
			'result' => $this->result,
			'bet_amount' => $this->bet_amount,
			'win_amount' => $this->win_amount,
			'created_at' => $this->created_at->toDateString(),
		];
	}
}
