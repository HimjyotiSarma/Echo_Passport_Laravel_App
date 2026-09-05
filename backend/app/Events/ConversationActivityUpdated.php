<?php

namespace App\Events;

use App\Enums\BroadcastEvent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationActivityUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Conversation $conversation,
        public User $actor,
        public BroadcastEvent $event
    ) {
    }

    public function broadcastOn(): array
    {
        return $this->conversation->participants()
            ->get()
            ->map(
                fn (User $user) => new PrivateChannel("user.{$user->id}")
            )
            ->all();
    }

    public function broadcastAs(): string
    {
        return 'conversation.activity.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation' => $this->conversation
                ->load([
                    'lastMessage',
                ])
                ->toResource(),
            'event' => $this->event->value
        ];
    }
}
