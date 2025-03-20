<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request)
    {
        // Create a new transaction with the validated data
        $transaction = Transaction::create([
            'user_id' => $request['user_id'],
            'type' => $request['type'],
            'money' => $request['money'],
            'chips' => $request['chips'],
            'card_number' => $request['card_number'],
            'cvv' => $request['cvv'],
            'expiration_date' => $request['expiration_date'],
        ]);

        // Return the created transaction as a resource
        return response()->json([
            'data' => $transaction
        ], 201);
    }
}