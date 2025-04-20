<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartGame implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $channelId;

	/**
	 * Create a new event instance.
	 */
	public function __construct($channelId)
	{
		$this->channelId = $channelId;
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
		return 'StartGame';
	}
}
