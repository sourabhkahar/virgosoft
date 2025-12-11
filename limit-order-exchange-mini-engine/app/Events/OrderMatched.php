<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyer;
    public $seller;
    public $trade;
    /**
     * Create a new event instance.
     */
    public function __construct($buyer, $seller, $trade)
    {
        logger('i am Here',[$buyer,$seller,$trade]);
        $this->buyer = $buyer;
        $this->seller = $seller;
        $this->trade = $trade;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->buyer->id),
            new PrivateChannel('user.' . $this->seller->id),
        ];
    }

    public function broadcastAs()
    {
        return 'OrderMatched';
    }

}
