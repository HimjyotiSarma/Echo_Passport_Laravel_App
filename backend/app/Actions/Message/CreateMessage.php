<?php

namespace App\Actions\Message;

use App\Enums\BroadcastEvent;
use App\Events\ConversationActivityUpdated;
use App\Events\MessageCreated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateMessage
{
    /**
     * Create a new class instance.
     */
    /**
     *@param array{
     *  body: string,
     *  reply_to?: string|null
     * } $data
     */
    public function handle(Conversation $conversation, User $sender, array $data): Message{
        return DB::transaction(function() use ($conversation, $sender, $data){
            if(isset($data['reply_to'])){
                if(! $conversation->messages()->whereKey($data['reply_to'])->exists()){
                    throw ValidationException::withMessages([
                        'reply_to' => 'The message you are replying to does not belong to this conversation'
                    ]);
                }
            }
            $message = new Message([
                'body' => $data['body'],
                'reply_to' => $data['reply_to'] ?? null
            ]);
            $message->conversation()->associate($conversation);
            $message->sender()->associate($sender);
            $message->save();
            $conversation->lastMessage()->associate($message);
            $conversation->save();
            $message->load([
                'sender',
                'conversation'
            ]);

            MessageCreated::dispatch($message);
            broadcast(new ConversationActivityUpdated($conversation, $sender, BroadcastEvent::MESSAGE_CREATED))->toOthers();
            return $message;
        });
    }
}
