<?php

namespace Tests\Unit\Services;

use App\Models\Comment;
use App\Models\Link;
use App\Models\User;
use App\Services\KarmaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\WithTenantContext;

class KarmaServiceTest extends TestCase
{
    use RefreshDatabase, WithTenantContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTenant();
    }

    public function test_it_calculates_karma_correctly()
    {
        $user = User::factory()->forTenant($this->tenant)->create();

        $publishedLinks = 10;
        $votesPerLink = 15;
        $discardedLinks = 5;
        $commentCount = 20;

        Link::factory()->count($publishedLinks)->forTenant($this->tenant)->create([
            'user_id' => $user->id,
            'status' => 'published',
            'votes' => $votesPerLink,
        ]);

        Link::factory()->count($discardedLinks)->forTenant($this->tenant)->create([
            'user_id' => $user->id,
            'status' => 'discard',
            'votes' => 0,
        ]);

        Comment::factory()->count($commentCount)->forTenant($this->tenant)->create([
            'user_id' => $user->id,
        ]);

        $karmaService = new KarmaService;
        $newKarma = $karmaService->updateUserKarma($user);

        $this->assertEquals($newKarma, $user->fresh()->karma);

        // Karma calculation breakdown:
        // Base: 6.0
        // + min(10 * 0.1, 2.0) = 1.0
        // + min(150 * 0.01, 3.0) = 1.5
        // + min(20 * 0.01, 1.0) = 0.2
        // - min(5 * 0.2, 2.0) = 1.0
        // Total expected: 6.0 + 1.0 + 1.5 + 0.2 - 1.0 = 7.7

        $this->assertEquals(7.7, $newKarma, '', 0.0001);
    }
}
