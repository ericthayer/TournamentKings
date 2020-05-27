<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Tournamentkings\Entities\Models\Placement;

class WonMatch
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $placement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Placement $placement)
    {
        $this->placement = $placement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * Keeping this around in case we want to use it later...
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
