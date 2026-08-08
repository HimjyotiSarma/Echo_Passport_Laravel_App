<?php

namespace App\Policies;

use App\Enums\ConversationRole;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if($user->isAdmin()){
            return true;
        }
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()
            ->whereKey($user->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        return $conversation->participants()
                    ->wherePivotIn('role', [
                        ConversationRole::ADMIN,
                        ConversationRole::OWNER
                    ])
                    ->whereKey($user->id)
                    ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return $conversation->isGroup() && $conversation->participants()
                    ->wherePivotIn('role', [
                        ConversationRole::ADMIN,
                        ConversationRole::OWNER
                    ])
                    ->whereKey($user->id)
                    ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Conversation $conversation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Conversation $conversation): bool
    {
        return false;
    }
}
