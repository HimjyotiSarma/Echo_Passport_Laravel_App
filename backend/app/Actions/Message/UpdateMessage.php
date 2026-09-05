<?php

namespace App\Actions\Message;

use App\Enums\BroadcastEvent;
use App\Events\ConversationActivityUpdated;
use App\Events\MessageUpdated;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateMessage
{
    /**
     * @param array{
     *  body: string
     * }$data
     */
    public function handle(Message $message, User $modifier, array $data): Message{
        return DB::transaction(function () use ($message, $modifier, $data){
            $message->fill([
                'body' => $data['body']
            ]);
            if($message->isDirty()){
                $message->save();
                MessageUpdated::dispatch($message);
                if($message->id === $message->conversation->last_message_id){
                     broadcast(new ConversationActivityUpdated($message->conversation, $modifier, BroadcastEvent::MESSAGE_UPDATED))->toOthers();
                }
            }
            return $message->load([
                'sender',
                'conversation'
            ]);
        });
    }
}
