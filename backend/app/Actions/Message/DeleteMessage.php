<?php

namespace App\Actions\Message;

use App\Enums\BroadcastEvent;
use App\Events\ConversationActivityUpdated;
use App\Events\MessageDeleted;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteMessage
{
    /**
     * Create a new class instance.
     */
    public function handle(Message $message, User $actor)
    {
        DB::transaction(function () use ($message, $actor){
            $conversation = $message->conversation;
            $message->delete();
            if($message->id === $conversation->last_message_id){
                    $conversation->lastMessage()->associate(
                        $conversation->messages()->latest('created_at')->first()
                    );

                    $conversation->save();
                broadcast(new ConversationActivityUpdated($message->conversation, $actor, BroadcastEvent::MESSAGE_DELETED))->toOthers();
            }
            MessageDeleted::dispatch($message);
        });
    }
}
