<?php

namespace App\Models;

use App\Enums\ConversationRole;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

#[Table(incrementing: false)]
class ConversationParticipant extends Pivot
{
    public const ATTRIBUTES = [
        'role',
        'joined_at',
        'last_read_message_id',
        'last_seen_at',
        'notification_enabled',
    ];
    
    protected $casts = [
        'role' => ConversationRole::class,
        'joined_at' => 'immutable_datetime',
        'last_seen_at' => 'immutable_datetime'
    ];

    public function joinDate(): Attribute {
        return Attribute::make(
            get: fn() => $this->joined_at?->toDateString()
        );
    }
    public function joinTime(): Attribute {
        return Attribute::make(
            get: fn() => $this->joined_at?->format('H:i:s'),
        );
    }

    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastReadMessage(): BelongsTo {
        return $this->belongsTo(Message::class, 'last_read_message_id', 'id');
    }
}
