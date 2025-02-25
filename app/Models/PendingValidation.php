<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class PendingValidation extends Model implements HasMedia
{
	use HasFactory, InteractsWithMedia;

	protected $fillable = [
		'user_id',
		'status',
		'image_url'
	];

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('pending_validation')
			->useFallbackUrl('/images/placeholder.jpg')
			->useFallbackPath(public_path('/images/placeholder.jpg'));
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
