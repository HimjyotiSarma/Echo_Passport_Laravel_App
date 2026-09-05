<?php

namespace App\Actions\ConversationParticipant;

use App\Actions\Message\CreateSystemMessage;
use App\Enums\ConversationRole;
use App\Enums\SystemMessageEvent;
use App\Events\ConversationParticipantAdded;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddConversationParticipant
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly CreateSystemMessage $createSystemMessage)
    {
    }
    public function handle(User $creator, User $user, Conversation $conversation, ConversationRole $role, bool $notificationEnable = true): ConversationParticipant
    {
        if($conversation->participants()->whereKey($user->id)->exists()){
            throw ValidationException::withMessages(
                [
                    'participants' => 'The User is already a member in the Conversation'
                ]
            );
        }
        $conversation->participants()->attachOrFail($user->id, [
            'role' => $role,
            'joined_at' => now(),
            'notification_enabled' =>$notificationEnable
        ]);

        $membership = $conversation->memberships()
                        ->where('user_id', $user->id)
                        ->with([
                            'user',
                            'conversation',
                        ])
                        ->firstOrFail();

        ConversationParticipantAdded::dispatch($membership, $creator);
        $this->createSystemMessage->handle(
            $conversation,
            $creator,
            SystemMessageEvent::PARTICIPANT_ADDED,
            [
                'participant_id' => $user->id
            ]
        );
        return $membership;

    }
}
