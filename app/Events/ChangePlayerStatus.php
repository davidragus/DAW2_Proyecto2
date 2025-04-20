<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangePlayerStatus implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $gameRoomId;
	public $userId;
	public $isReady;

	/**
	 * Create a new event instance.
	 */
	public function __construct($gameRoomId, $userId, $isReady)
	{
		$this->gameRoomId = $gameRoomId;
		$this->userId = $userId;
		$this->isReady = $isReady;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('bingo-' . $this->gameRoomId);
	}

	public function broadcastAs()
	{
		return 'ChangePlayerStatus';
	}

	public function broadcastWith(): array
	{
		return [
			'id' => $this->userId,
			'isReady' => $this->isReady
		];
	}
}
