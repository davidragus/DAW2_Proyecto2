<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DrawNumber implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $number;
	public $channelId;

	/**
	 * Create a new event instance.
	 */
	public function __construct($number, $channelId)
	{
		$this->number = $number;
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
		return 'DrawNumber';
	}

	public function broadcastWith(): array
	{
		return [
			'number' => $this->number
		];
	}
}
