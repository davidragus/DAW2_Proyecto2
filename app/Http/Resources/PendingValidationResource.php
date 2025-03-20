<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PendingValidationResource extends JsonResource
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
			'user' => [
				'name' => $this->user->name,
				'surname1' => $this->user->surname1,
				'surname2' => $this->user->surname2,
				'dni' => $this->user->dni,
				'birthdate' => $this->user->birthdate,
				'gender' => $this->user->gender,
				'country' => $this->user->country->name,
				'email' => $this->user->email,
			],
			'status' => $this->status,
			'created_at' => $this->created_at->toDateString(),
			'image' => $this->getMedia('pending_validations')[0]->getFullUrl(),
		];
	}
}
