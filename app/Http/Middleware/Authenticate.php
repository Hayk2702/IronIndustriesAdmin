<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Closure;

class Authenticate extends Middleware
{

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $lang = $request->getSession()->get('lang');
            return route('login', ['locale' => $lang ? $lang : app()->getLocale()]);
        }
    }
}
