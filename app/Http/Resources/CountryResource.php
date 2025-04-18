<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'code' => $this->code,
			'name' => $this->name
		];
	}
}
