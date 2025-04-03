<?php

namespace Tests\Unit\Services;

use App\Models\Link;
use App\Models\Tenant;
use App\Models\User;
use App\Services\LinkPromotionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\WithTenantContext;

class LinkPromotionServiceTest extends TestCase
{
    use RefreshDatabase, WithTenantContext;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupTenant();
    }

    public function test_it_promotes_eligible_links()
    {
        Config::set('app.repostea.min_karma_for_promotion', 3);
        Config::set('app.repostea.min_hours_for_promotion', 2);

        $user = User::factory()->create(['karma' => 5]);

        $eligibleLink = Link::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'karma' => 5,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        // Not eligible: too new
        Link::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'karma' => 5,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        // Not eligible: low karma
        Link::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'karma' => 1,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        // Create a different tenant with an eligible link that should NOT be promoted
        $otherTenant = $this->setupAnotherTenant();
        $eligibleLinkInOtherTenant = Link::factory()->forTenant($otherTenant)->create([
            'user_id' => User::factory()->forTenant($otherTenant)->create(['karma' => 5])->id,
            'status' => 'pending',
            'karma' => 5,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        Tenant::setCurrentTenant($this->tenant);

        $service = new LinkPromotionService;
        $promotedCount = $service->promoteLinks();

        $this->assertEquals(2, $promotedCount);

        $this->assertDatabaseHas('links', [
            'id' => $eligibleLink->id,
            'status' => 'published',
            'tenant_id' => $this->tenant->uuid,
        ]);

        $this->assertNotNull($eligibleLink->fresh()->promoted_at);

        $this->assertEquals(6, $user->fresh()->karma); // karma +1
    }
}
