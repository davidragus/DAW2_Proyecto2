<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'description' => $this->description,
            'achievement_type' => $this->achievement_type,
            'amount' => $this->amount,
            'reward_amount' => $this->reward_amount,
			'image' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,
            'created_at' => $this->created_at->toDateString()
		];
	}
}
