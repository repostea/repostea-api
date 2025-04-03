<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || (! Auth::user()->moderator && ! Auth::user()->admin)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized access',
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'You do not have permission to access this section');
        }

        return $next($request);
    }
}
