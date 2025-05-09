<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
	use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

	protected $fillable = [
		'username',
		'name',
		'surname1',
		'surname2',
		'email',
		'dni',
		'gender',
		'phone_number',
		'birthdate',
		'password',
		'country_code',
		'validated',
		'chips',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'chips' => 'integer',
	];

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new UserResetPasswordNotification($token));
	}

	public function pendingValidations()
	{
		return $this->hasMany(PendingValidation::class);
	}

	public function gamesHistory()
	{
		return $this->hasMany(GameRoomsPlayersHistory::class, 'user_id', 'id');
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'user_id', 'id');
	}

	public function country()
	{
		return $this->hasOne(Country::class, 'code', 'country_code');
	}

	public function achievements()
	{
		return $this->belongsToMany(Achievement::class, 'users_has_achievements')
			->withPivot('obtained_date')
			->withTimestamps();
	}

	// public function games()
	// {
	// 	return $this->belongsToMany(Game::class, 'users_game_rooms_plays')
	// 		->withPivot('bet_amount', 'win_amount', 'result')
	// 		->withTimestamps();
	// }

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('images/user_avatar')
			->useFallbackUrl('/images/placeholder.jpg')
			->useFallbackPath(public_path('/images/placeholder.jpg'));
	}

	public function registerMediaConversions(Media $media = null): void
	{
		if (env('RESIZE_IMAGE') === true) {

			$this->addMediaConversion('resized-image')
				->width(env('IMAGE_WIDTH', 300))
				->height(env('IMAGE_HEIGHT', 300));
		}
	}
}
