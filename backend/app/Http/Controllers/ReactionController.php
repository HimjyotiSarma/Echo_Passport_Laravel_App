<?php

namespace App\Http\Controllers;

use App\Actions\Reaction\DeleteReaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReactionRequest;
use App\Actions\Reaction\SetReaction;
use App\Models\Message;
use App\Models\Reaction;
use Illuminate\Support\Facades\Gate;

class ReactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request, Message $message, SetReaction $action)
    {
        Gate::authorize('create', [Reaction::class, $message]);

        $reaction = $action->handle($message, $request->user(), $request->validated());

        return $reaction->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reaction $reaction, DeleteReaction $action)
    {
        Gate::authorize('delete', $reaction);

        $action->handle($reaction);

        return response()->noContent();
    }
}
