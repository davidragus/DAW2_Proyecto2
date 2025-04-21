<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
			'name' => $this->name,
            'route_path' => $this->route_path,
			'active' => $this->active == 0 ? false : true,
			'image' => $this->getFirstMediaUrl('games'),
			'icon' => $this->getFirstMediaUrl('games_icons'),
			'created_at' => $this->created_at->toDateString(),
			'updated_at' => $this->updated_at->toDateString(),
		];
	}
}
