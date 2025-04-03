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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('source')->nullable()->comment('meneame, reddit, activitypub, etc');
            $table->string('external_id')->nullable()->index();
            $table->string('type')->default('internal');
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('original_external_url')->nullable();
            $table->text('content')->nullable();
            $table->text('description');
            $table->string('status')->default('pending'); // pending, published, discard
            $table->integer('votes')->default(1);
            $table->integer('clicks')->default(0);
            $table->integer('karma')->default(0);
            $table->dateTime('promoted_at')->nullable();
            $table->string('image')->nullable();
            $table->boolean('nsfw')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
