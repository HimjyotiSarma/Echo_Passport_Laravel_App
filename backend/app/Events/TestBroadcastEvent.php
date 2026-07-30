<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public string $message;

    /**
     * Put this event onto the "broadcasts" queue.
     */
    public string $broadcastQueue = 'broadcasts';

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'test.broadcast';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
