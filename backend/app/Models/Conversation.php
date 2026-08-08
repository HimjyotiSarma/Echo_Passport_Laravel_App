<?php

namespace App\Models;

use App\Enums\ConversationRole;
use App\Enums\ConversationType;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table(key: 'id', keyType: 'string', incrementing: false)]
class Conversation extends Model
{
    use HasUlids, SoftDeletes;

    protected $casts = [
        'type' => ConversationType::class,
    ];
    protected $fillable = [
        'type',
        'name',
        'description',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function isDirect(){
        return $this->type === ConversationType::DIRECT;
    }

    public function isGroup(){
        return $this->type === ConversationType::GROUP;
    }

    public function creatorRole(): ConversationRole {
        return $this->isGroup() ? ConversationRole::OWNER : ConversationRole::MEMBER;
    }

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }

    public function memberships(): HasMany {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function lastMessage(): BelongsTo {
        return $this->belongsTo(Message::class, 'last_message_id', 'id');
    }

    public function lastMessageTime() {
        return Attribute::make(
            get: fn() => $this->lastMessage?->created_at
        );
    }

    public function participants(): BelongsToMany {
        return $this->belongsToMany(User::class, 'conversation_participants', 'conversation_id', 'user_id')
                ->using(ConversationParticipant::class)
                ->as('membership')
                ->withPivot(ConversationParticipant::ATTRIBUTES);
    }

}
