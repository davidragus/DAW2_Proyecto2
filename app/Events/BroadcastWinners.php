<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastWinners implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $winners;
	public $channelId;

	public function __construct($winners, $channelId)
	{
		$this->winners = $winners;
		$this->channelId = $channelId;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('bingo');
	}

	public function broadcastAs()
	{
		return 'BroadcastWinners';
	}

	public function broadcastWith(): array
	{
		return [
			'lineWinners' => $this->winners['line'],
			'bingoWinners' => $this->winners['bingo'],
		];
	}

}
