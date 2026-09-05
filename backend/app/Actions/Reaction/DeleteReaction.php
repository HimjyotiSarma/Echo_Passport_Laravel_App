<?php

namespace App\Actions\Reaction;
use App\Models\Reaction;
use App\Events\ReactionDeleted;

class DeleteReaction
{
    /**
     * Create a new class instance.
     */
    public function handle(Reaction $reaction)
    {
        $reaction->load('message');

        $reaction->delete();

        ReactionDeleted::dispatch($reaction);
    }
}
