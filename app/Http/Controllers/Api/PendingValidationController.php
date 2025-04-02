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
	public function show($id)
	{
		$validation = PendingValidation::find($id);
		return new PendingValidationResource($validation);
	}

	public function approve($id)
	{
		try {
			$validation = PendingValidation::find($id);
			$validation->status = 'ACCEPTED';
			$validation->save();
			return response()->json(['message' => 'The validation has been approved.', 'data' => $validation], 200);
		} catch (\Exception $ex) {
			return response()->json(['message' => 'An unexpected error has occurred.'], 500);
		}

	}

	public function decline($id)
	{
		try {
			$validation = PendingValidation::find($id);
			$validation->status = 'DENIED';
			$validation->save();
			return response()->json(['message' => 'The validation has been declined.', 'data' => $validation], 200);
		} catch (\Exception $ex) {
			return response()->json(['message' => 'An unexpected error has occurred.'], 500);
		}
	}
}
