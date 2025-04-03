<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || ! Auth::user()->admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized access',
                ], 403);
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
