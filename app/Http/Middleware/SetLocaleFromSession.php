<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;


class SetLocaleFromSession
{
     public function handle($request, Closure $next)
    { 
    if ($locale = $request->session()->get('locale')) {
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        
        config(['app.locale' => $locale]);
        
        $translator = app('translator');
        $translator->setLocale($locale);
    }

    return $next($request);
}}