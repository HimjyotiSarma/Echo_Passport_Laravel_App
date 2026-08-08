<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'message_id',
    'user_id',
    'emoji_code',
])]
#[Table(key: 'id', keyType: 'string', incrementing: false)]
class Reaction extends Model
{
    use HasUlids;

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function message(): BelongsTo {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }

    public function  user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
