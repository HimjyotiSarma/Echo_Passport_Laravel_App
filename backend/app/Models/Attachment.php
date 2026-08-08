<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[Fillable([
    'message_id',
    'disk',
    'object_key',
    'original_name',
    'mime_type',
    'size',
    'metadata'
])]
#[Table(key: 'id', keyType: 'string', incrementing: false)]
class Attachment extends Model
{
    use HasUlids;

    protected $casts = [
        'size' => 'integer',
        'metadata' => 'array',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected function extension(): Attribute {
        return Attribute::make(
            get: fn() => strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION))
        );
    }

    public function humanReadableSize(): Attribute {
        return Attribute::make(
            get: function() {
                $bytes = $this->size;
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                for($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++){
                    $bytes /= 1024.0;
                }
                return round($bytes, 2) . ' ' . $units[$i];
            }
        );
    }

    public function message(): BelongsTo {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}
