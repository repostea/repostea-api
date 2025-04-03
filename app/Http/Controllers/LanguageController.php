<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (! in_array($locale, ['en', 'es'], true)) {
            $locale = 'en';
        }

        if (Auth::check()) {
            $user = Auth::user();
            $user->lang = $locale;
            $user->save();
        }
        Session::put('locale', $locale);

        return redirect()->back();
    }
}
