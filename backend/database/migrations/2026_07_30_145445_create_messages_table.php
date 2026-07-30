<?php

use App\Enums\MessageType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('sender_id')->constrained('users','id', indexName: 'message_sender_idx')->cascadeOnDelete();
            $table->string('type')->default(MessageType::TEXT->value);
            $table->text('body')->nullable();
            $table->foreignUlid('reply_to')->nullable()->constrained('messages', 'id', indexName: 'reply_to_idx')->nullOnDelete();
            $table->timestampTz('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'created_at'], 'conversation_message_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
