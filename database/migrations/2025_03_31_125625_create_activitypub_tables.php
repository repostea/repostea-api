<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('follower_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('actor_uri')->nullable();
            $table->string('inbox_url')->nullable();
            $table->boolean('is_local')->default(true);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'actor_uri']);
            $table->index('actor_uri');
        });

        Schema::create('following', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('followed_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('actor_uri')->nullable();
            $table->string('inbox_url')->nullable();
            $table->boolean('is_local')->default(true);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'actor_uri']);
            $table->index('actor_uri');
        });

        Schema::create('inbox_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('activity_id')->nullable();
            $table->string('type');
            $table->string('actor')->nullable();
            $table->string('object_type')->nullable();
            $table->string('object_id')->nullable();
            $table->json('data');
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('activity_id');
            $table->index('actor');
            $table->index('processed');
        });

        Schema::create('outbox_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_id');
            $table->string('type');
            $table->string('object_type')->nullable();
            $table->string('object_id')->nullable();
            $table->json('data');
            $table->boolean('delivered')->default(false);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('activity_id');
            $table->index('delivered');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_remote')->default(false);
            $table->string('remote_actor_uri')->nullable()->unique();
            $table->string('remote_inbox')->nullable();
            $table->string('shared_inbox')->nullable();
        });

        Schema::create('remote_link_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->onDelete('cascade');
            $table->string('actor_uri');
            $table->integer('value');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(['link_id', 'actor_uri']);
        });

        Schema::create('remote_comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->string('actor_uri');
            $table->integer('value');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(['comment_id', 'actor_uri']);
        });

        Schema::create('remote_announces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->onDelete('cascade');
            $table->string('actor_uri');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->unique(['link_id', 'actor_uri']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('remote_announces');
        Schema::dropIfExists('remote_comment_votes');
        Schema::dropIfExists('remote_link_votes');
        Schema::dropIfExists('outbox_activities');
        Schema::dropIfExists('inbox_activities');
        Schema::dropIfExists('following');
        Schema::dropIfExists('followers');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_remote');
            $table->dropColumn('remote_actor_uri');
            $table->dropColumn('remote_inbox');
            $table->dropColumn('shared_inbox');
        });
    }
};
