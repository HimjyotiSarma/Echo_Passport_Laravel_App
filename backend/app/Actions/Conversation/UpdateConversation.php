<?php

namespace App\Actions\Conversation;

use App\Events\ConversationUpdated;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UpdateConversation
{

    /**
     * @param array {
     *     name?: string|null,
     *     description?: string|null
     * }$data
     *
     */
    public function handle(User $modifier, Conversation $conversation, array $data): Conversation
    {
        if($conversation->isDirect()){
            throw ValidationException::withMessages([
                'conversation' => 'Only group conversations can be updated.',
            ]);
        }
        $conversation->fill($data);
        if($conversation->isDirty()){
            $conversation->save();
            ConversationUpdated::dispatch($conversation, $modifier);
        }
        return $conversation;
    }
}
