<?php

namespace App\Actions\Message;

use App\Enums\MessageType;
use App\Enums\SystemMessageEvent;
use App\Events\MessageCreated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class CreateSystemMessage
{
    /**
     * Invoke the class instance.
     */
    public function handle(Conversation $conversation, User $sender, SystemMessageEvent $event, array $metadata): Message
    {
        $message = new Message([
            'type' => MessageType::SYSTEM,
            'metadata' => [
                'event' => $event,
                ...$metadata
            ],
        ]);
        $message->conversation()->associate($conversation);
        $message->sender()->associate($sender);
        $message->save();
        $conversation->lastMessage()->associate($message);
        $conversation->save();

        MessageCreated::dispatch($message);
        return $message;
    }
}
