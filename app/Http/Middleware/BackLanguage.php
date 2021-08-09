<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class BackLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('back_locale')) {
            App::setLocale(session()->get('back_locale'));
        }
        return $next($request);
    }
}
