<?php

namespace App\Actions\ConversationParticipant;

use App\Enums\ConversationRole;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AddConversationParticipant
{
    /**
     * Create a new class instance.
     */
    public function handle(User $user, Conversation $conversation, ConversationRole $role, bool $notificationEnable = true): ConversationParticipant
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

        return $conversation->participants()->findOrFail($user->id)->membership;

    }
}
