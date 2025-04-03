<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Services\TenantScopeManager;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            $tenant = Tenant::getCurrentTenant();
            $model->tenant_id = $tenant->uuid ?? null;
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (TenantScopeManager::isGlobalTenantScopeEnabled()) {
                $tenant = Tenant::getCurrentTenant();
                if (! empty($tenant)) {
                    $builder->where('tenant_id', $tenant->uuid);
                }
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
