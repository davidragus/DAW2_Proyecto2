<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Country::create([
			'code' => 'ESP',
			'name' => 'Spain',
		]);
		Country::create([
			'code' => 'USA',
			'name' => 'United States of America',
		]);
		Country::create([
			'code' => 'GBR',
			'name' => 'United Kingdom',
		]);
		Country::create([
			'code' => 'FRA',
			'name' => 'France',
		]);
		Country::create([
			'code' => 'MEX',
			'name' => 'Mexico',
		]);
	}
}
