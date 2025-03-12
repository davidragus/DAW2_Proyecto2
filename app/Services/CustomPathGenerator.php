<?php

namespace App\Services;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class CustomPathGenerator implements PathGenerator
{
	public function getPath(Media $media): string
	{
		return $this->getBasePath($media) . '/';
	}

	public function getPathForConversions(Media $media): string
	{
		return $this->getBasePath($media) . '/conversions/';
	}

	public function getPathForResponsiveImages(Media $media): string
	{
		return $this->getBasePath($media) . '/responsive-images/';
	}

	protected function getBasePath(Media $media): string
	{
		$prefix = $media->collection_name;

		if ($prefix !== '') {
			return $prefix . '/' . $media->model_id;
		}

		return $media->getKey();
	}
}