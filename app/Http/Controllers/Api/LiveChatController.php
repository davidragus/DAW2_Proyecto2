<?php

namespace App\Http\Controllers\Api;

use App\Events\SendMessage;
use App\Http\Resources\ChatMessageResource;
use App\Models\ChatMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
	public function getMessages()
	{
		$messages = ChatMessage::orderBy('created_at', 'desc')->get();
		return ChatMessageResource::collection($messages);
	}

	public function sendMessage(Request $request)
	{
		$message = new ChatMessage();
		$message->user_id = $request->message['user'];
		$message->message = $request->message['message'];

		if ($message->save()) {
			broadcast(new SendMessage(new ChatMessageResource($message)));
		}
	}
}
