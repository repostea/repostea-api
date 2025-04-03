<?php

namespace Tests\Feature\Api;

use App\Models\Comment;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\WithApiKeyHeaders;
use Tests\Traits\WithTenantContext;

class CommentApiTest extends TestCase
{
    use RefreshDatabase, WithApiKeyHeaders, WithFaker, WithTenantContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTenant();
    }

    public function test_can_list_comments_for_link()
    {
        $user = User::factory()->create();

        $link = Link::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
        ]);

        $comments = [];
        for ($i = 0; $i < 3; $i++) {
            $comments[] = Comment::factory()->create([
                'link_id' => $link->id,
                'user_id' => $user->id,
                'parent_id' => null,
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            Comment::factory()->create([
                'link_id' => $link->id,
                'user_id' => $user->id,
                'parent_id' => $comments[0]->id,
            ]);
        }
        $response = $this->getJsonWithKey("/api/v1/links/{$link->id}/comments");
        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'votes',
                        'karma',
                        'user',
                        'replies',
                    ],
                ],
            ]);
    }

    public function test_can_view_single_comment()
    {
        $link = Link::factory()->create();
        $comment = Comment::factory()->create([
            'link_id' => $link->id,
        ]);

        $response = $this->getJsonWithKey("/api/v1/comments/{$comment->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'votes',
                    'karma',
                    'user',
                    'replies',
                ],
            ]);
    }

    public function test_authenticated_user_can_create_comment()
    {
        $user = User::factory()->create([
            'karma' => config('app.repostea.min_karma_for_comment'),
        ]);
        $link = Link::factory()->create([
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/comments", [
                'content' => 'This is a test comment',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'content',
                    'votes',
                    'karma',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'link_id' => $link->id,
        ]);
    }

    public function test_user_can_reply_to_comment()
    {
        $user = User::factory()->create([
            'karma' => config('app.repostea.min_karma_for_comment'),
        ]);
        $link = Link::factory()->create();
        $comment = Comment::factory()->create([
            'link_id' => $link->id,
        ]);
        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/comments", [
                'content' => 'This is a reply',
                'parent_id' => $comment->id,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a reply',
            'user_id' => $user->id,
            'link_id' => $link->id,
            'parent_id' => $comment->id,
        ]);
    }

    public function test_user_with_low_karma_cannot_comment()
    {
        $user = User::factory()->create([
            'karma' => config('app.repostea.min_karma_for_comment') - 1,
        ]);
        $link = Link::factory()->create();

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/comments", [
                'content' => 'This is a test comment',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => trans('app.not_enough_karma'),
            ]);
    }

    public function test_authenticated_user_can_vote_comment()
    {
        $user = User::factory()->create([
            'karma' => config('app.repostea.min_karma_for_comment'),
        ]);
        $comment = Comment::factory()->create([
            'votes' => 0,
        ]);

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/comments/{$comment->id}/vote", [
                'value' => 1,
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'votes',
                    'karma',
                ],
                'message',
            ]);

        $this->assertEquals(1, Comment::find($comment->id)->votes);
    }

    public function test_user_with_low_karma_cannot_negative_vote_comment()
    {
        $user = User::factory()->create([
            'karma' => config('app.repostea.min_karma_for_comment') - 1,
        ]);
        $comment = Comment::factory()->create();

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/comments/{$comment->id}/vote", [
                'value' => -1,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => trans('app.not_enough_karma'),
            ]);
    }

    public function test_user_can_list_their_comments()
    {
        $user = User::factory()->create();
        Comment::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->getJsonWithKey('/api/v1/user/comments');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }
}
