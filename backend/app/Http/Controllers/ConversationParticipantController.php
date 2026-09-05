<?php

namespace App\Http\Controllers;

use App\Actions\Conversation\UpdateConversation;
use App\Actions\ConversationParticipant\AddConversationParticipant;
use App\Actions\ConversationParticipant\AddConversationParticipants;
use App\Actions\ConversationParticipant\ListConversationParticipants;
use App\Actions\ConversationParticipant\RemoveConversationParticipant;
use App\Actions\ConversationParticipant\UpdateConversationParticipant;
use App\Http\Requests\StoreConversationParticipantsRequest;
use App\Http\Requests\UpdateConversationParticipantRequest;
use App\Http\Resources\ConversationParticipantResource;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ConversationParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Conversation $conversation, ListConversationParticipants $action)
    {
        Gate::authorize('view', $conversation);
        return ConversationParticipantResource::collection($action->handle($conversation));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationParticipantsRequest $request, Conversation $conversation , AddConversationParticipants $action)
    {
        Gate::authorize('addParticipant', $conversation);
        $participants = $action->handle($request->user(), $conversation, $request->validated());
        return ConversationParticipantResource::collection($participants);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the conversation_participant resource(limited to roles and notification).
     * joined_at, last_read_message_id, and last_seen_at should be changed by their respective operations
     */
    public function update(UpdateConversationParticipantRequest $request, Conversation $conversation, User $participant ,UpdateConversationParticipant $action)
    {
        Gate::authorize('updateParticipant', [$conversation, $participant]);
        $membership = $action->handle($conversation, $request->user() ,$participant, $request->validated());
        return new ConversationParticipantResource($membership);
    }

    /**
     * Remove the specified user from conversation(except self).
     */
    public function destroy(Request $request, Conversation $conversation, User $participant, RemoveConversationParticipant $action){
        Gate::authorize('removeParticipant', [$conversation, $participant]);
        $action->handle($conversation, $request->user(), $participant);
        return response()->noContent();
    }

}
