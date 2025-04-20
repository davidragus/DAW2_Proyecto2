<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\GameRoomPlayerHistoryResource;
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
		$users = User::when(request('search_id'), function ($query) {
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

		if ($user->save()) {
			if ($request->hasFile('avatar')) {
				$user->addMediaFromRequest('avatar')->toMediaCollection('user_avatar');
			}
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
		$user->dni = $request->dni;
		$user->country_code = $request->country;
		$user->phone_number = $request->phone_number;

		if (!empty($request->password)) {
			$user->password = Hash::make($request->password) ?? $user->password;
		}

		if ($request->hasFile('avatar')) {
			$user->clearMediaCollection('user_avatar');
			$user->addMediaFromRequest('avatar')->toMediaCollection('user_avatar');
		}

		if ($user->save()) {
			if ($role) {
				$user->syncRoles($role);
			}
			return new UserResource($user);
		}
	}

	public function getChips($id)
	{
		$user = User::find($id);
		if ($user) {
			return response()->json(['chips' => $user->chips], 200);
		} else {
			return response()->json(['message' => 'User not found'], 404);
		}
	}

	public function updateChips(Request $request, $id)
	{
		// TODO: Refactor when possible (front and back)
		$user = User::find($id);
		$user->chips = $request->chips;
		if ($user->update()) {
			return response()->json($user);
		}
	}


	public function updateImg(Request $request, $id)
	{
		$user = User::find($id);
		// Delete previous image
		$user->clearMediaCollection('user_avatar');
		$user->addMediaFromRequest('file')->toMediaCollection('user_avatar');
		return new UserResource($user);
	}

	public function destroy(User $user)
	{
		$this->authorize('user-delete');
		$user->delete();

		return response()->noContent();
	}

	public function getUserAchievements($userId)
	{
		$user = User::with('achievements')->findOrFail($userId);

		return response()->json([
			'achievements' => $user->achievements->map(function ($achievement) {
				return [
					'id' => $achievement->id,
					'name' => $achievement->name,
					'description' => $achievement->description,
					'image' => $achievement->getFirstMediaUrl('Achievements'), // Obtén la URL de la imagen
					'achievement_type' => $achievement->achievement_type,
					'amount' => $achievement->amount,
					'reward_amount' => $achievement->reward_amount,
					'obtained_date' => $achievement->pivot->obtained_date, // Datos de la tabla intermedia
				];
			}),
		]);
	}
	public function createNewValidation(Request $request, $id)
	{
		$user = User::find($id);
		if ($user) {
			$validation = PendingValidation::create(['user_id' => $user->id, 'status' => 'PENDING']);
			foreach ($request->file('validationImages') as $index => $image) {
				// Asignar un nombre descriptivo a cada imagen
				$imageName = match ($index) {
					0 => 'dni_front',
					1 => 'dni_back',
					2 => 'face_image',
					default => 'unknown_image',
				};

				// Guardar la imagen en la colección 'pending_validations'
				$validation->addMedia($image)
					->usingFileName($imageName . '.' . $image->getClientOriginalExtension())
					->toMediaCollection('pending_validations');
			}
			return response()->json(['message' => $request->files], 200);
		} else {
			return response()->json(['message' => 'User not found'], 404);
		}
	}

	public function getGameHistory($userId)
	{
		$user = User::find($userId);

		$history = $user->gamesHistory()
			->orderBy('created_at', 'desc')
			->paginate(50);

		return GameRoomPlayerHistoryResource::collection($history);
	}

	public function getBalanceHistory($userId)
	{
		$user = User::find($userId);

		$gameHistory = $user->gamesHistory()
			->orderBy('created_at', 'desc')
			->paginate(50);

		$gameHistory = $gameHistory->flatMap(function ($game) {
			if ($game->win_amount) {
				return array_values([
					[
						'name' => 'Win',
						'created_at' => $game->created_at->toDateString(),
						'type' => 'PLUS',
						'amount' => $game->win_amount
					],
					[
						'name' => 'Bet',
						'created_at' => $game->created_at->toDateString(),
						'type' => 'MINUS',
						'amount' => $game->bet_amount
					]
				]);
			} else {
				return [
					[
						'name' => 'Bet',
						'created_at' => $game->created_at->toDateString(),
						'type' => 'MINUS',
						'amount' => $game->bet_amount
					]
				];
			}
		});

		$transactions = $user->transactions()
			->orderBy('created_at', 'desc')
			->paginate(50);

		$transactions = $transactions->flatMap(function ($transaction) {
			return [
				[
					'name' => $transaction->type === 'DEPOSIT' ? 'Deposit' : 'Withdraw',
					'created_at' => $transaction->created_at->toDateString(),
					'type' => $transaction->type === 'DEPOSIT' ? 'PLUS' : 'MINUS',
					'real_money' => $transaction->money,
					'amount' => $transaction->chips
				]
			];
		});

		$finalArray = $gameHistory->concat($transactions)
			->sortByDesc('created_at')
			->values();

		return response()->json(['data' => $finalArray], 200);
	}

	public function updatePassword(Request $request, $id)
	{
		$user = User::find($id);
		// Validate the current password
		if (!Hash::check($request->current_password, $user->password)) {
			return response()->json(['message' => 'Current password is incorrect'], 400);
		}
		// Validate the new password
		if ($request->new_password !== $request->confirm_password) {
			return response()->json(['message' => 'New password and confirmation do not match'], 400);
		}
		// Update the password
		$user->password = Hash::make($request->new_password);
		if ($user->save()) {
			return response()->json(['message' => 'Password updated successfully'], 200);
		} else {
			return response()->json(['message' => 'Failed to update password'], 500);
		}
	}
}
