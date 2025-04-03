<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = 'en';

        if (Auth::check() && Auth::user()->lang !== null) {
            $locale = Auth::user()->lang;
        } elseif (Session::has('locale')) {
            $locale = Session::get('locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
