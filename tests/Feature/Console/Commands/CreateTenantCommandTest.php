<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTenantCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_new_tenant_with_api_key()
    {
        $initialCount = Tenant::count();

        $tenantName = 'Test Tenant';
        $this->artisan('tenant:create', ['name' => $tenantName])
            ->expectsOutput('Tenant created successfully:')
            ->assertExitCode(0);

        $this->assertEquals($initialCount + 1, Tenant::count());

        $tenant = Tenant::where('name', $tenantName)->first();

        $this->assertNotNull($tenant);
        $this->assertEquals($tenantName, $tenant->name);
        $this->assertNotNull($tenant->uuid);
        $this->assertNotNull($tenant->api_key);
        $this->assertEquals(32, strlen($tenant->api_key));
    }
}
