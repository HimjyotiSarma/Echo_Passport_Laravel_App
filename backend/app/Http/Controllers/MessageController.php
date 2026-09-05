<?php

namespace App\Http\Controllers;

use App\Actions\Message\CreateMessage;
use App\Actions\Message\DeleteMessage;
use App\Actions\Message\ListMessage;
use App\Actions\Message\UpdateMessage;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Conversation $conversation, ListMessage $action)
    {
        // Authorize in Coversation Policy
        Gate::authorize('view', $conversation);
        return MessageResource::collection($action->handle($conversation));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request, Conversation $conversation, CreateMessage $action): JsonResponse
    {
        Gate::authorize('create', [Message::class, $conversation]);
        $message = $action->handle($conversation,
                $request->user(),
                $request->validated());
        return $message->toResource()->response()->setStatusCode(201);
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message, UpdateMessage $action)
    {
        Gate::authorize('update', $message);
        $message = $action->handle($message, $request->user(), $request->validated());
        return $message->toResource()->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message, DeleteMessage $action)
    {
        Gate::authorize('delete', $message);
        $action->handle($message, request()->user());
        return response()->noContent();
    }
}
