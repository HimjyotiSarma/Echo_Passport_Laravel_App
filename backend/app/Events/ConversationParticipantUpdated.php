<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationParticipantUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ConversationParticipant $membership, public User $modifier)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $conversation = $this->membership->conversation;
        return $conversation->participants()
                    ->where('users.id', '!=', $this->modifier->id)
                    ->pluck('users.id')
                    ->map(
                        fn(string $userId) => new PrivateChannel("users.{$userId}")
                    )->all();
    }

    public function broadcastAs(){
        return 'conversation.participant.updated';
    }

    public function broadcastWith(){
        return [
            'participant' => $this->membership->toResource()
        ];
    }
}
