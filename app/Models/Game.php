<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Game extends Model implements HasMedia
{
	use HasFactory, InteractsWithMedia;

	protected $fillable = [
		'name',
		'route_path'
	];

	// public function users()
	// {
	//     return $this->belongsToMany(User::class, 'users_game_rooms_plays')
	//         ->withPivot('bet_amount', 'win_amount', 'result', 'created_at', 'updated_at')
	//         ->withTimestamps();
	// }

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('games')
			->useFallbackUrl('/images/games')
			->useFallbackPath(public_path('/images/games'));
	}

	// public function registerMediaConversions(Media $media = null): void
	// {
	// 	$this->addMediaConversion('thumb')
	// 		->width(300)
	// 		->height(300)
	// 		->sharpen(10);
	// }
}
