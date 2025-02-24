<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
			'name' => $this->name,
			'surname1' => $this->surname1,
			'surname2' => $this->surname2,
			'email' => $this->email,
			'role_id' => $this->roles,
			'roles' => $this->roles,
			'role_name' => $this->roles->pluck('name')->first(),
			'dni' => $this->dni,
			'last_validation' => $this->pendingValidations->last() ? $this->pendingValidations->last() : 0,
			'phone_number' => $this->phone_number,
			'gender' => $this->gender,
			'birthdate' => $this->birthdate,
			'avatar' => count($this->getMedia('*')) > 0 ? $this->getMedia('*')[0]->getUrl() : null,
			'created_at' => $this->created_at->toDateString()
		];
	}
}
