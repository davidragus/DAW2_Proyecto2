<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendCountdown implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $seconds;
	public $gameRoomId;

	/**
	 * Create a new event instance.
	 */
	public function __construct($gameRoomId, $seconds)
	{
		$this->gameRoomId = $gameRoomId;
		$this->seconds = $seconds;
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
		return 'SendCountdown';
	}

	public function broadcastWith(): array
	{
		return [
			'seconds' => $this->seconds
		];
	}
}
