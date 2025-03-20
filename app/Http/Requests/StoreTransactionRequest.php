<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambia esto según tus necesidades de autorización
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:DEPOSIT,WITHDRAWAL',
            'money' => 'required|numeric',
            'chips' => 'required|integer',
            'card_number' => 'required|string',
            'cvv' => 'required|integer',
            'expiration_date' => 'required|date',
        ];
    }
}