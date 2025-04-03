<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-Repostea-Api-Key');
        $tenantId = $request->header('X-Repostea-Id');

        if (! $this->authenticateTenant($tenantId, $apiKey)) {
            return response()->json([
                'message' => 'Unauthorized',
                'hint' => 'Check that REPOSTEA_ID and REPOSTEA_API_KEY are correct in your .env file. For testing, use REPOSTEA_ID=DEMO and REPOSTEA_API_KEY=DEMO',
            ], 401);
        }

        return $next($request);
    }

    private function authenticateTenant(?string $tenantId, ?string $apiKey): bool
    {
        if (empty($tenantId) || empty($apiKey)) {
            return false;
        }
        $tenant = Tenant::where('uuid', $tenantId)
            ->where('api_key', $apiKey)
            ->first();

        if (empty($tenant)) {
            return false;
        }
        Tenant::setCurrentTenant($tenant);

        return true;
    }
}
