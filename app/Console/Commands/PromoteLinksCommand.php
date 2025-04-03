<?php

namespace App\Console\Commands;

use App\Services\LinkPromotionService;

class PromoteLinksCommand extends TenantAwareCommand
{
    protected $signature = 'repostea:promote-links {--tenant= : Specific tenant UUID}';

    protected $description = 'Automatically promote links that meet the criteria';

    protected LinkPromotionService $promotionService;

    public function __construct(LinkPromotionService $promotionService)
    {
        parent::__construct();
        $this->promotionService = $promotionService;
    }

    protected function handleForTenant(): int
    {
        $this->info('Promoting links...');

        $count = $this->promotionService->promoteLinks();

        $this->info("{$count} links have been promoted.");

        return 0;
    }
}
