<?php

namespace App\Actions\ConversationParticipant;

use App\Actions\Message\CreateSystemMessage;
use App\Enums\ConversationRole;
use App\Enums\SystemMessageEvent;
use App\Events\ConversationParticipantUpdated;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateConversationParticipant
{
    public function __construct(private readonly CreateSystemMessage $createSystemMessage)
    {
    }
    /**
     * @param array{
     *     role?: ConversationRole|string,
     *     notification_enabled?: bool
     * } $data
     */
    public function handle(
        Conversation $conversation,
        User $modifier,
        User $participant,
        array $data
    ): ConversationParticipant {
        return DB::transaction(function () use ($conversation, $modifier, $participant, $data) {
            $membership = $conversation->memberships()
            ->where('user_id', $participant->id)
            ->first();

            if (! $membership) {
                throw ValidationException::withMessages([
                    'participant' => 'The user is not a participant in the conversation.',
                ]);
            }
            $previousRole = $membership->role;
            $membership->fill($data);

            if ($membership->isDirty()) {
                $membership->save();

                if ($membership->wasChanged('role')) {
                    // This is to ensure that for a invidual's settings updated,
                    // No broadcast takes place
                    $this->createSystemMessage->handle(
                            $conversation,
                            $modifier,
                            SystemMessageEvent::PARTICIPANT_ROLE_CHANGED,
                            [
                                'participant_id' => $participant->id,
                                'prev_role' => $previousRole->value,
                                'current_role' => $membership->role->lcg_value
                            ]
                    );
                    $membership->load([
                        'user',
                        'conversation',
                    ]);
                    ConversationParticipantUpdated::dispatch(
                        $membership,
                        $modifier
                    );
                }
            }

            return $membership->load('user');
        });
    }
}
