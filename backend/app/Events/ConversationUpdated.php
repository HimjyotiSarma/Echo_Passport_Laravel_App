<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Conversation $conversation, public User $modifier)
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
        return $this->conversation->participants()
                    ->where('users.id','!=', $this->modifier->id)
                    ->get()
                    ->map(
                        fn(User $user) => new PrivateChannel("users.{$user->id}")
                    )
                    ->all();
    }

    public function broadcastAs(){
        return 'conversation.updated';
    }

    public function broadcastWith(){
        return [
            'conversation' => $this->conversation->load([
                'participants',
                'lastMessage'
            ])->toResource(),
        ];
    }
}
