<?php

namespace App\Actions\Conversation;

use App\Events\ConversationDeleted;
use App\Models\Conversation;
use App\Models\User;

class DeleteConversation
{
    /**
     * Create a new class instance.
     */
    public function handle(User $modifier, Conversation $conversation)
    {
        $conversation->delete();
        ConversationDeleted::dispatch($conversation, $modifier);
    }
}
