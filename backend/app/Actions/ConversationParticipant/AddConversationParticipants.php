<?php

namespace App\Actions\ConversationParticipant;

use App\Enums\ConversationRole;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

class AddConversationParticipants
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly AddConversationParticipant $addParticipant)
    {
        //
    }

    /**
     * @param list<string> $userIds
     * @return list<ConversationParticipant>
     */
    public function handle(User $creator, Conversation $conversation, array $userIds)
    {
        return DB::transaction(function() use ($creator, $userIds, $conversation) {
            $users = User::query()->whereKey($userIds)->get();
            $participants = [];

            foreach($users as $user){
                $participants[] = $this->addParticipant->handle(
                                            $creator,
                                            $user,
                                            $conversation,
                                            ConversationRole::MEMBER);
            };

            return $participants;
        });
    }
}
