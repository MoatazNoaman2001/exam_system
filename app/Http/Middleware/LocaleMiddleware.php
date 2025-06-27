<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(2); // /lang/ar

        if (!in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        App::setLocale($locale);
        Session::put('locale', $locale); // خزّن اللغة في السيشن

        return redirect()->back(); // رجّع المستخدم للمكان اللي كان فيه
    }
}
