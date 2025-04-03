<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenant:create {name : Tenant name}';

    protected $description = 'Creates a new tenant with an API key';

    public function handle(): int
    {
        $name = $this->argument('name');

        $tenant = Tenant::create([
            'name' => $name,
            'api_key' => Str::random(32),
        ]);

        $this->info('Tenant created successfully:');
        $this->info('UUID: '.$tenant->uuid);
        $this->info('Name: '.$tenant->name);
        $this->info('API Key: '.$tenant->api_key);

        return 0;
    }
}
