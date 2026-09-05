<?php

namespace App\Actions\ConversationParticipant;

use App\Models\Conversation;
use Illuminate\Contracts\Pagination\CursorPaginator;

class ListConversationParticipants
{

    /**
     * Invoke the class instance.
     */
    public function handle(Conversation $conversation): CursorPaginator
    {
        return $conversation->participants()->orderByRaw("
            CASE WHEN conversation_participants.role
                WHEN 'owner' THEN 1
                WHEN 'admin' THEN 2
                WHEN 'member' THEN 3
            END
        ")
        ->orderBy('users.name')
        ->orderBy('conversation_participants.joined_at', 'DESC')
        ->cursorPaginate(50);
    }
}
