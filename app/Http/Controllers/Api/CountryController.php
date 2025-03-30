<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
	public function getCountries()
	{
		$countries = Country::orderBy('name', 'asc')->get();
		return new CountryResource($countries);
	}
}
