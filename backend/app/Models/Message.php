<?php

namespace App\Models;

use App\Enums\MessageType;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Hidden(['deleted_at'])]
#[Fillable(['type', 'body', 'conversation_id', 'reply_to'])]
#[Table(key: 'id', keyType: 'string', incrementing: false)]
class Message extends Model
{
    use HasUlids, SoftDeletes;

    protected $casts = [
        'type' => MessageType::class,
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function replyTo(): BelongsTo {
        return $this->belongsTo(Message::class, 'reply_to', 'id');
    }

    public function replies(): HasMany {
        return $this->hasMany(Message::class, 'reply_to', 'id');
    }

    public function attachments(): HasMany {
        return $this->hasMany(Attachment::class, 'message_id', 'id');
    }

    public function reactions(): HasMany {
        return $this->hasMany(Reaction::class);
    }

}
