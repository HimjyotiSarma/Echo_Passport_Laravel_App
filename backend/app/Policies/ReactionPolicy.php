<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reaction $reaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Message $message): bool
    {
        return $message
            ->conversation()
            ->participants()
            ->whereKey($user->id)
            ->exists()
            &&
            $message->sender_id !== $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reaction $reaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reaction $reaction): bool
    {
        return $reaction->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reaction $reaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reaction $reaction): bool
    {
        return false;
    }
}
