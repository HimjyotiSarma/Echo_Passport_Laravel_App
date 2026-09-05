<?php

namespace App\Actions\Reaction;

use App\Events\ConversationActivityUpdated;
use App\Events\ReactionCreated;
use App\Events\ReactionUpdated;
use App\Models\Message;
use App\Models\Reaction;
use App\Models\User;

class SetReaction
{
    /**
     * Create a new class instance.
     */
    /**
     * @param array{
     *  emoji_code: string
     * }$data
     */
    public function handle(Message $message, User $user, array $data)
    {
        $reaction = $message->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['emoji_code' => $data['emoji_code']]
        );
        if($reaction->wasRecentlyCreated){
            ReactionCreated::dispatch($reaction);
        }else{
            ReactionUpdated::dispatch($reaction);
        }
        return $reaction;
    }
}
