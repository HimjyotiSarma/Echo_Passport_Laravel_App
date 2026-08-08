<?php

namespace App\Http\Controllers;

use App\Actions\Conversation\CreateConversation;
use App\Actions\Conversation\DeleteConversation;
use App\Actions\Conversation\ListConversation;
use App\Actions\Conversation\UpdateConversation;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Requests\UpdateConversationRequest;
use App\Http\Resources\ConversationCollection;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ListConversation $action): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Conversation::class);
        return ConversationResource::collection($action->handle($request->user()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request, CreateConversation $action): ConversationResource
    {
        Gate::authorize('create', Conversation::class);
        $conversation = $action->handle($request->user(), $request->validated());
        return new ConversationResource($conversation);
    }

    /**
     * Display the specified resource.
     * Add this method logic if a conversation has extra features or info to display
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConversationRequest $request, Conversation $conversation, UpdateConversation $action)
    {
        Gate::authorize('update', $conversation);
        $conversation = $action->handle($request->user(), $conversation, $request->validated());

        return new ConversationResource($conversation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Conversation $conversation, DeleteConversation $action)
    {
        Gate::authorize('delete', $conversation);
        $action->handle($request->user(), $conversation);
        return response()->noContent();
    }
}
