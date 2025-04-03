<?php

namespace App\Console\Commands;

use App\Services\KarmaService;

class UpdateKarmaCommand extends TenantAwareCommand
{
    protected $signature = 'repostea:update-karma {--tenant= : Specific tenant UUID}';

    protected $description = 'Update the karma of all users';

    protected KarmaService $karmaService;

    public function __construct(KarmaService $karmaService)
    {
        parent::__construct();
        $this->karmaService = $karmaService;
    }

    protected function handleForTenant(): int
    {
        $this->info('Updating user karma...');

        $count = $this->karmaService->updateAllUsersKarma();

        $this->info("Karma updated for {$count} users.");

        return 0;
    }
}
