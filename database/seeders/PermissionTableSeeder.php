<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$permissions = [
			'role-list',
			'role-create',
			'role-edit',
			'role-delete',
			'permission-list',
			'permission-create',
			'permission-edit',
			'permission-delete',
			'user-list',
			'user-create',
			'user-edit',
			'user-delete',
			'validation-list',
			'validation-show',
			'validation-approve',
			'validation-decline',
			'post-list',
			'post-create',
			'post-edit',
			'post-all',
			'post-delete',
			'achievement-create',
			'achievement-edit',
			'achievement-delete',
			'achievement-list',
			'games-list',
			'game-create',
			'game-edit',
			'validation-delete',
			'gameRooms-list',
			'gameRooms-create',
			'gameRooms-edit',
			'gameRooms-delete',
		];

		foreach ($permissions as $permission) {
			Permission::create(['name' => $permission]);
		}
	}
}
