<?php

namespace App\Actions\Message;

use App\Models\Conversation;
use Illuminate\Pagination\CursorPaginator;

class ListMessage
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function handle(Conversation $conversation): CursorPaginator{
        return $conversation->messages()
                ->with([
                    'replyTo',
                    'sender',
                    'reaction',
                    'attchments'
                ])
                ->lastest('created_at')
                ->cursorPaginate(50);
    }
}
