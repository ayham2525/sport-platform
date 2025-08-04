<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestEvent implements ShouldBroadcast
{
    public function broadcastOn(): array
    {
        return ['test-channel'];
    }

    public function broadcastWith(): array
    {
        return ['message' => '🔥 WebSocket is working!'];
    }
}
