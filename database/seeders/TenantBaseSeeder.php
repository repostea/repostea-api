<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

abstract class TenantBaseSeeder extends Seeder
{
    protected ?Tenant $tenant = null;

    public function __construct(?Tenant $tenant = null)
    {
        $this->tenant = $tenant;
    }

    public function seed(?Tenant $tenant = null): void
    {

        if ($tenant) {
            $this->tenant = $tenant;
        }

        if (! $this->tenant) {
            return;
        }

        Tenant::setCurrentTenant($this->tenant);

        $this->runSeed();
    }

    abstract protected function runSeed(): void;
}
