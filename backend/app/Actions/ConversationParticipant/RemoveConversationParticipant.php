<?php

namespace App\Actions\ConversationParticipant;

use App\Actions\Message\CreateSystemMessage;
use App\Enums\SystemMessageEvent;
use App\Events\ConversationParticipantRemoved;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RemoveConversationParticipant
{
    public function __construct(private readonly CreateSystemMessage $createSystemMessage)
    {
    }
    public function handle(Conversation $conversation, User $modifier ,User $participant): void
    {
        DB::transaction(function () use ($conversation, $modifier, $participant) {
            $conversationParticipant = $conversation->memberships()
                                        ->where('user_id', $participant->id)
                                        ->first();
            if(! $conversationParticipant){
                throw ValidationException::withMessages([
                    'participant' => 'The user is not a participant in the conversation'
                ]);
            }

            $conversation->participants()->detach($participant->id);

            $this->createSystemMessage->handle(
                $conversation,
                $modifier,
                SystemMessageEvent::PARTICIPANT_REMOVED,
                [
                    'participant_id' => $participant->id,
                ]
            );
            ConversationParticipantRemoved::dispatch(
                $conversation,
                $participant,
                $modifier,
            );
        });
    }
}
