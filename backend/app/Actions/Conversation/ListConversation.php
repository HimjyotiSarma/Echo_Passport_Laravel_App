<?php

namespace App\Actions\Conversation;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Query\JoinClause;

use Illuminate\Support\Collection;

class ListConversation
{
    /**
     * Create a new class instance.
     */
    /**
     *
     */
    public function handle(User $user): CursorPaginator
    {
        return $user->conversations()
                ->with([
                    'participants',
                    'lastMessage',
                ])
                ->select('conversations.*')
                ->selectSub(
                    Message::query()
                        ->selectRaw('COUNT(*)')
                        ->join('conversation_participants as cp', function (JoinClause $join) use ($user) {
                            $join->on('cp.conversation_id', '=', 'messages.conversation_id')
                                ->where('cp.user_id', '=', $user->id);
                        })
                        ->whereColumn(
                            'messages.conversation_id',
                            'conversations.id'
                        )
                        ->where(function ($query) {
                            $query->whereNull('cp.last_seen_at')
                                ->orWhereColumn(
                                    'messages.created_at',
                                    '>',
                                    'cp.last_seen_at'
                                );
                        }),
                    'unread_message_count'
                )
                ->leftJoin(
                    'messages as last_message',
                    'last_message.id',
                    '=',
                    'conversations.last_message_id'
                )
                ->orderByDesc('last_message.created_at')
                ->orderByDesc('conversations.id')
                ->cursorPaginate(15);
    }
}
