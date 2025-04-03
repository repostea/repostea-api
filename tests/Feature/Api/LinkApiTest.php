<?php

namespace Tests\Feature\Api;

use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\WithApiKeyHeaders;
use Tests\Traits\WithTenantContext;

class LinkApiTest extends TestCase
{
    use RefreshDatabase, WithApiKeyHeaders, WithFaker, WithTenantContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTenant();
    }

    public function test_can_list_published_links()
    {
        Link::factory()->count(5)->forTenant($this->tenant)->create([
            'status' => 'published',
            'promoted_at' => now(),
        ]);

        $response = $this->getJsonWithKey('/api/v1/links');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'total',
                ],
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_can_list_pending_links()
    {
        Link::factory()->count(3)->create([
            'status' => 'pending',
        ]);

        $response = $this->getJsonWithKey('/api/v1/links/pending');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_view_single_link()
    {
        $link = Link::factory()->create([
            'status' => 'published',
            'clicks' => 5,
        ]);

        $tags = Tag::factory()->count(2)->create();
        $link->tags()->attach($tags->pluck('id'));

        $response = $this->getJsonWithKey('/api/v1/links/'.$link->id);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'votes',
                    'karma',
                    'user',
                    'tags',
                ],
            ]);

        $this->assertEquals(6, $link->fresh()->clicks);
    }

    public function test_authenticated_user_can_create_link()
    {
        $user = User::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        $linkData = [
            'title' => 'Test Link',
            'url' => 'https://example.com',
            'description' => 'Test description',
            'tags' => $tags->pluck('id')->toArray(),
        ];

        $response = $this->actingAs($user)
            ->postJsonWithKey('/api/v1/links', $linkData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'url',
                    'description',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('links', [
            'title' => 'Test Link',
            'url' => 'https://example.com',
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'value' => 1,
        ]);
    }

    public function test_authenticated_user_can_vote_link()
    {
        $user = User::factory()->create([
            'karma' => 10,
        ]);
        $link = Link::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJsonWithKey("/api/v1/links/{$link->id}/vote", [
                'value' => 1,
            ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'votes' => 1,
                ],
                'message' => trans('app.vote_registered'),
            ]);

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'link_id' => $link->id,
            'value' => 1,
        ]);
    }

    public function test_user_cannot_vote_twice_on_same_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create();

        $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/vote", [
                'value' => 1,
            ]);

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/vote", [
                'value' => 1,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => trans('app.already_voted'),
            ]);
    }

    public function test_user_with_low_karma_cannot_negative_vote()
    {
        $user = User::factory()->create([
            'karma' => 3,
        ]);
        $link = Link::factory()->create();

        $response = $this->actingAs($user)
            ->postJsonWithKey("/api/v1/links/{$link->id}/vote", [
                'value' => -1,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => trans('app.not_enough_karma'),
            ]);
    }

    public function test_user_can_see_voted_links()
    {
        $user = User::factory()->create();
        $link = Link::factory()->count(5)->create();

        for ($i = 0; $i < 3; $i++) {
            $user->votes()->create([
                'link_id' => $link[$i]->id,
                'value' => 1,
                'karma_value' => $user->karma / 6,
            ]);
        }

        $response = $this->actingAs($user)
            ->getJsonWithKey('/api/v1/user/links/voted');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }
}
