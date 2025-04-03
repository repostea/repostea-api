<?php

namespace Tests\Traits;

use App\Models\Tenant;

trait WithTenantContext
{
    protected Tenant $tenant;

    protected function setupTenant(): void
    {
        $this->tenant = Tenant::factory()->create();
        Tenant::setCurrentTenant($this->tenant);
    }

    protected function setupAnotherTenant(): Tenant
    {
        $otherTenant = Tenant::factory()->create();
        Tenant::setCurrentTenant($otherTenant);

        return $otherTenant;
    }
}
