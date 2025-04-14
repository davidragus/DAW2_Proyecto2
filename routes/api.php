<?php

use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\PendingValidationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('game/getGameRoom/{id}', [GameController::class, 'getGameRoom']);
Route::get('game/getPlayer/{gameRoomId}/{playerId}', [GameController::class, 'getPlayer']);
Route::get('game/getPlayersStatus/{gameRoomId}', [GameController::class, 'getPlayersStatus']);
Route::post('game/startGame/{gameRoomId}', [GameController::class, 'startGame']);
Route::post('game/joinGame/{id}', [GameController::class, 'joinGame']);
Route::post('game/updatePlayerGameData/{gameRoomId}/{playerId}', [GameController::class, 'updatePlayerGameData']);
Route::post('game/callLine/{gameRoomId}/{playerId}', [GameController::class, 'callLine']);
Route::post('game/updatePlayerStatus/{gameRoomId}/{playerId}', [GameController::class, 'updatePlayerStatus']);

Route::group(['middleware' => 'auth:sanctum'], function () {

	Route::get('getMessages', [\App\Http\Controllers\Api\LiveChatController::class, 'getMessages']);
	Route::post('sendMessage', [\App\Http\Controllers\Api\LiveChatController::class, 'sendMessage']);

	Route::apiResource('users', UserController::class);
	// Route::get('users', [UserController::class, 'index']);
	// Route::get('users/{user}', [UserController::class, 'show']);
	// Route::post('users', [UserController::class, 'store']);
	Route::post('users/{user}', [UserController::class, 'update']);
	// Route::delete('users/{user}', [UserController::class, 'destroy']);

	Route::post('users/updateImg/{id}', [UserController::class, 'updateImg']);
	Route::get('users/getChips/{id}', [UserController::class, 'getChips']);
	Route::put('users/updateChips/{id}', [UserController::class, 'updateChips']);

	Route::apiResource('posts', PostControllerAdvance::class);
	Route::apiResource('categories', CategoryController::class);
	Route::apiResource('roles', RoleController::class);

	Route::get('role-list', [RoleController::class, 'getList']);
	Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
	Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
	Route::apiResource('permissions', PermissionController::class);

	Route::get('category-list', [CategoryController::class, 'getList']);
	Route::get('/user', [ProfileController::class, 'user']);
	Route::put('/user', [ProfileController::class, 'update']);

	Route::get('abilities', function (Request $request) {
		return $request->user()->roles()->with('permissions')
			->get()
			->pluck('permissions')
			->flatten()
			->pluck('name')
			->unique()
			->values()
			->toArray();
	});

	Route::post('/user/{id}/submit-validation', [UserController::class, 'createNewValidation']);
	Route::apiResource('validations', PendingValidationController::class);
	Route::get('validation/{id}', [PendingValidationController::class, 'show']);
	Route::put('validation/approve/{id}', [PendingValidationController::class, 'approve']);
	Route::put('validation/decline/{id}', [PendingValidationController::class, 'decline']);
	Route::delete('validation/{id}', [PendingValidationController::class, 'destroy']);
});

Route::get('category-list', [CategoryController::class, 'getList']);

Route::get('get-posts', [PostControllerAdvance::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostControllerAdvance::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostControllerAdvance::class, 'getPost']);

Route::get('countries', [CountryController::class, 'getCountries']);

Route::post('/transactions', [TransactionController::class, 'store']);

Route::get('achievements', [AchievementController::class, 'index']);
Route::get('achievements/{achievement}', [AchievementController::class, 'show']);
Route::post('achievements/{achievement}', [AchievementController::class, 'update']);
Route::post('achievements', [AchievementController::class, 'store']);
Route::delete('achievements/{achievement}', [AchievementController::class, 'destroy']);

Route::get('/users/{userId}/achievements', [UserController::class, 'getUserAchievements']);