<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * قائمة الميدل وير اللي بتشتغل على كل طلب (Global Middleware)
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * مجموعات الميدل وير المرتبطة بأنواع معينة من الطلبات
     *
     * @var array<string, array<int, class-string|string>>
     */
  protected $middlewareGroups = [
   'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\SetLocale::class,


],



        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * الميدل وير اللي ممكن تربطها بروتات معينة بالاسم
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // هنا ضفت ميدل وير خاصة بيكي
        'admin' => \App\Http\Middleware\IsAdmin::class,
        'student' => \App\Http\Middleware\IsStudent::class,
        // 'locale' => \App\Http\Middleware\LocaleMiddleware::class,
        'setlocale' => \App\Http\Middleware\SetLocale::class,
    ];
    protected $middlewarePriority = [

    \Illuminate\Session\Middleware\StartSession::class,
    \App\Http\Middleware\SetLocale::class, // ← لازم يجي بعد StartSession مباشرة
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Routing\Middleware\ThrottleRequests::class,
    \Illuminate\Auth\Middleware\Authorize::class,


];
}
