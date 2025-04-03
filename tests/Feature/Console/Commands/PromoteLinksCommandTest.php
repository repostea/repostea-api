<?php

namespace Tests\Feature\Console\Commands;

use App\Services\LinkPromotionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PromoteLinksCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calls_promote_links_on_service()
    {
        $mock = Mockery::mock(LinkPromotionService::class);
        $mock->shouldReceive('promoteLinks')->once()->andReturn(7);

        $this->app->instance(LinkPromotionService::class, $mock);

        $this->artisan('repostea:promote-links')
            ->expectsOutput('Promoting links...')
            ->expectsOutput('7 links have been promoted.')
            ->assertExitCode(0);
    }
}
