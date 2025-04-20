<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LineCalled implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $userId;
	public $channelId;

	/**
	 * Create a new event instance.
	 */
	public function __construct($channelId, $userId)
	{
		$this->channelId = $channelId;
		$this->userId = $userId;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('bingo-' . $this->channelId);
	}

	public function broadcastAs()
	{
		return 'LineCalled';
	}

	public function broadcastWith(): array
	{
		return [
			'playerId' => $this->userId
		];
	}
}
