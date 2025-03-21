<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\PendingValidation;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index()
	{
		$orderColumn = request('order_column', 'created_at');
		if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
			$orderColumn = 'created_at';
		}
		$orderDirection = request('order_direction', 'desc');
		if (!in_array($orderDirection, ['asc', 'desc'])) {
			$orderDirection = 'desc';
		}
		$users = User::
			when(request('search_id'), function ($query) {
				$query->where('id', request('search_id'));
			})
			->when(request('search_title'), function ($query) {
				$query->where('name', 'like', '%' . request('search_title') . '%');
			})
			->when(request('search_global'), function ($query) {
				$query->where(function ($q) {
					$q->where('id', request('search_global'))
						->orWhere('name', 'like', '%' . request('search_global') . '%');

				});
			})
			->orderBy($orderColumn, $orderDirection)
			->paginate(50);

		return UserResource::collection($users);
	}

	public function store(StoreUserRequest $request)
	{
		$role = Role::find($request->role_id);
		$user = new User();
		$user->name = $request->name;
		$user->surname1 = $request->surname1;
		$user->surname2 = $request->surname2;
		$user->dni = $request->dni;
		$user->birthdate = $request->birthdate;
		$user->phone_number = $request->phone_number;
		$user->gender = $request->gender;
		$user->email = $request->email;
		$user->username = $request->username;

		$user->password = Hash::make('test1234');

		$test = public_path();

		if ($user->save()) {
			if ($role) {
				$user->assignRole($role);
			}
			if ($request->automaticValidation && $request->hasFile('validationImages')) {
				$validation = PendingValidation::create(['user_id' => $user->id, 'status' => 'ACCEPTED']);
				$validation->addMediaFromRequest('validationImages')->toMediaCollection('pending_validations');
			}
			return new UserResource($user);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return UserResource
	 */
	public function show(User $user)
	{
		$user->load('roles')->get();
		return new UserResource($user);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateUserRequest $request
	 * @param User $user
	 * @return UserResource
	 */
	public function update(UpdateUserRequest $request, User $user)
	{
		$role = Role::find($request->role_id);

		$user->name = $request->name;
		$user->email = $request->email;
		$user->surname1 = $request->surname1;
		$user->surname2 = $request->surname2;
		$user->country_code = $request->country;
		$user->phone_number = $request->phone_number;
		$user->chips = $request->chips;

		if (!empty($request->password)) {
			$user->password = Hash::make($request->password) ?? $user->password;
		}

		if ($user->save()) {
			if ($role) {
				$user->syncRoles($role);
			}
			return new UserResource($user);
		}
	}

	public function updateChips(Request $request, User $id)
	{
		$user = User::find($id);
		$user->chips = $request->chips;
		$user->save();
		return response()->json($user);
	}


	public function updateimg(Request $request)
	{

		$user = User::find($request->id);

		if ($request->hasFile('picture')) {
			$user->media()->delete();
			$media = $user->addMediaFromRequest('picture')->preservingOriginal()->toMediaCollection('images-users');

		}
		$user = User::with('media')->find($request->id);
		return $user;
	}

	public function destroy(User $user)
	{
		$this->authorize('user-delete');
		$user->delete();

		return response()->noContent();
	}


}
