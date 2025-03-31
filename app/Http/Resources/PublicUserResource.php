<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicUserResource extends JsonResource
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
			'username' => $this->username,
			'role_name' => $this->roles->pluck('name')->first(),
			'avatar' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,
		];
	}
}
