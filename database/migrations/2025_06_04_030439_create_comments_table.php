<?php

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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->text('body');
            $table->integer('reputation_points')->default(0);
            $table->timestamps();
        });

        // Create votes table in same migration to ensure proper order
        Schema::create('comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['upvote', 'downvote']);
            $table->timestamps();
            $table->unique(['user_id', 'comment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_votes'); // Drop votes first due to FK
        Schema::dropIfExists('comments');
    }
};
