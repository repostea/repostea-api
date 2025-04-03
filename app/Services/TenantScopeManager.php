<?php

namespace App\Services;

use Closure;

class TenantScopeManager
{
    /**
     * Variable estática para controlar el estado global del scope
     */
    private static bool $globalTenantScopeEnabled = true;

    /**
     * Obtiene el estado actual del scope global
     */
    public static function isGlobalTenantScopeEnabled(): bool
    {
        return self::$globalTenantScopeEnabled;
    }

    /**
     * Desactiva el scope de tenant para todos los modelos
     */
    public static function disableGlobalTenantScope(): void
    {
        self::$globalTenantScopeEnabled = false;
    }

    /**
     * Reactiva el scope de tenant para todos los modelos
     */
    public static function enableGlobalTenantScope(): void
    {
        self::$globalTenantScopeEnabled = true;
    }

    /**
     * Ejecuta un callback con el scope de tenant desactivado
     * para todos los modelos y luego restaura el estado anterior
     *
     * @return mixed
     */
    public static function withoutGlobalTenantScope(Closure $callback)
    {
        $originalState = self::$globalTenantScopeEnabled;
        self::disableGlobalTenantScope();

        try {
            return $callback();
        } finally {
            self::$globalTenantScopeEnabled = $originalState;
        }
    }
}
