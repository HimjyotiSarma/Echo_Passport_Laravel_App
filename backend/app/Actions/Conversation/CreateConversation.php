<?php

namespace App\Actions\Conversation;

use App\Actions\ConversationParticipant\AddConversationParticipant;
use App\Enums\ConversationRole;
use App\Models\User;
use App\Enums\ConversationType;
use App\Events\ConversationCreated;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class CreateConversation
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly AddConversationParticipant $addParticipant
    )
    {}
    /**
     * @param array{
     *      type: ConversationType | string,
     *      name?: string|null,
     *      description?: string|null,
     *      participants: list<string>
     * }$data
     */
    public function handle(User $creator, array $data): Conversation
    {
        return DB::transaction(function () use($creator, $data) {
            $conversation = new Conversation([
                'name' => $data['name'] ?? null,
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
            ]);
            $conversation->createdBy()->associate($creator);
            $conversation->save();
            $this->addParticipant->handle(
                    $creator,
                    $conversation,
                    $conversation->creatorRole()
            );
            $participants = User::query()->whereKey($data['participants'])->get();
            foreach($participants as $participant){
                if($participant->is($creator)){
                    continue;
                }
                $this->addParticipant->handle(
                    $participant,
                    $conversation,
                    ConversationRole::MEMBER
                );
            }
            ConversationCreated::dispatch($conversation->load('participants'));

            return $conversation;
        });
    }
}
