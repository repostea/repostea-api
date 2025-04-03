<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMinimumKarma
{
    public function handle(Request $request, Closure $next, $minimumKarma)
    {
        if (! Auth::check() || Auth::user()->karma < (float) $minimumKarma) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => trans('app.not_enough_karma'),
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', trans('app.not_enough_karma'));
        }

        return $next($request);
    }
}
