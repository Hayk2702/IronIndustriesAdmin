<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class MultiLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $locale = \Illuminate\Support\Facades\Request::segment(1);
        if (!in_array($locale, ['am','ru','en']))
        {
            URL::defaults(['locale' => app()->getLocale()]);
            return redirect(config('app.locale') . '/' . request()->path());
        }
        app()->setLocale($locale);
        $request->route()->forgetParameter('locale');
        return $next($request);
    }
}
