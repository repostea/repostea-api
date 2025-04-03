<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

abstract class TenantAwareCommand extends Command
{
    /**
     * Executes the command for each tenant or a specific one
     */
    public function handle()
    {
        $tenantId = $this->option('tenant');

        if (! empty($tenantId)) {
            $tenant = Tenant::where('uuid', $tenantId)->first();

            if ($tenant === null) {
                $this->error("Tenant not found: {$tenantId}");

                return 1;
            }

            Tenant::setCurrentTenant($tenant);

            return $this->handleForTenant();
        }

        // If no tenant is specified, run for all
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->name}");
            Tenant::setCurrentTenant($tenant);
            $result = $this->handleForTenant();

            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }

    /**
     * Executes the command for the current tenant
     */
    abstract protected function handleForTenant(): int;
}
