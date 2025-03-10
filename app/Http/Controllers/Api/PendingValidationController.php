<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PendingValidation;
use App\Http\Resources\PendingValidationResource;
use Illuminate\Http\Request;

class PendingValidationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$orderColumn = request('order_column', 'created_at');
		if (!in_array($orderColumn, ['id', 'created_at'])) {
			$orderColumn = 'created_at';
		}
		$orderDirection = request('order_direction', 'desc');
		if (!in_array($orderDirection, ['asc', 'desc'])) {
			$orderDirection = 'desc';
		}

		$validations = PendingValidation::orderBy($orderColumn, $orderDirection)
			->paginate(50);

		return PendingValidationResource::collection($validations);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(PendingValidation $pendingValidation)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(PendingValidation $pendingValidation)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, PendingValidation $pendingValidation)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(PendingValidation $pendingValidation)
	{
		//
	}
}
