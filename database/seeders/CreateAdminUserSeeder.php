<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => 1,
            'name' => 'Admin',
            'surname1' => 'Demo',
            'username' => 'Demo',
            'email' => 'admin@demo.com',
            'dni' => '12345678A',
            'gender' => 'M',
            'phone_number' => '123456789',
            'password' => bcrypt('12345678'),
            'birthdate' => '1990-01-01',
        ]);

        $role = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'user']);
        $permissions = [
            'post-list',
            'post-create',
            'post-edit',
            'post-delete',
            'post-list',
            'achievement-create',
			'achievement-edit',
			'achievement-delete',
			'achievement-list',
        ];
        $role2->syncPermissions($permissions);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        $user = User::create([
            'id' => 2,
            'name' => 'User',
            'surname1' => 'User',
            'username' => 'user',
            'email' => 'user@demo.com',
            'dni' => '12345678B',
            'gender' => 'M',
            'phone_number' => '123456789',
            'password' => bcrypt('12345678'),
            'birthdate' => '1990-01-01',
        ]);
        $user->assignRole([$role2->id]);

    }
}
