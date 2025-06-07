<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('comment_votes')) {
            Schema::create('comment_votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('comment_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['upvote', 'downvote']);
                $table->timestamps();
                $table->unique(['user_id', 'comment_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_votes');
    }
};
