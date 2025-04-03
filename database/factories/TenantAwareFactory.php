<?php

namespace Database\Factories;

use App\Models\Tenant;

/**
 * Trait to add tenant support to factories
 */
trait TenantAwareFactory
{
    public function forTenant($tenant = null)
    {
        return $this->state(function (array $attributes) use ($tenant) {
            if (is_null($tenant)) {
                $tenant = Tenant::getCurrentTenant();
            }

            return [
                'tenant_id' => $tenant->uuid,
            ];
        });
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function ($model) {
            if (empty($model->tenant_id)) {
                $tenant = Tenant::getCurrentTenant();
                $model->tenant_id = $tenant->uuid;
            }
        });
    }
}
