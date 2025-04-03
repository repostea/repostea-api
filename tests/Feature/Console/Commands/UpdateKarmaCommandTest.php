<?php

namespace Tests\Feature\Console\Commands;

use App\Services\KarmaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UpdateKarmaCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calls_update_karma_on_service()
    {
        $mock = Mockery::mock(KarmaService::class);
        $mock->shouldReceive('updateAllUsersKarma')->once()->andReturn(42);

        $this->app->instance(KarmaService::class, $mock);

        $this->artisan('repostea:update-karma')
            ->expectsOutput('Updating user karma...')
            ->expectsOutput('Karma updated for 42 users.')
            ->assertExitCode(0);
    }
}
