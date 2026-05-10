<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pastes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 32)->unique();
            $table->string('title')->nullable();
            $table->longText('content');
            $table->string('language')->default('text');
            $table->string('password_hash')->nullable();
            $table->enum('visibility', ['private', 'unlisted', 'public'])->default('unlisted');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('burn_after_reading')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->unsignedInteger('max_views')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->string('delete_token', 128)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['visibility', 'created_at']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pastes');
    }
};
