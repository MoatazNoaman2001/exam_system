<?php

  namespace App\Providers;

  use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;  
use Illuminate\Http\Request;
  use Illuminate\Support\Facades\RateLimiter;
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\Auth;
  use Carbon\Carbon;
use App\Channels\CustomDatabaseChannel;
use Illuminate\Notifications\ChannelManager;
  use Illuminate\Support\Facades\App;
  use Illuminate\Support\Facades\Session;

  class AppServiceProvider extends ServiceProvider
  {
      /**
       * The path to the "home" route for your application.
       *
       * This is used by Laravel authentication to redirect users after login.
       *
       * @var string
       */
      public const HOME = '/completed-action'; // عدّل إلى الوجهة المطلوبة

      /**
       * Define your route model bindings, pattern filters, etc.
       */
     public function boot(): void
{

     $this->app->make(ChannelManager::class)->extend('custom_database', function ($app) {
            return new CustomDatabaseChannel();
        });
    // 1. ضبط اللغة قبل أي عمليات أخرى
    $this->setApplicationLocale();

    // 2. تكوين معدل التقييد
    $this->configureRateLimiting();

    // 3. تحميل الروتات
    
    

}

protected function setApplicationLocale(): void
{
    // حل شامل لضمان تطبيق اللغة
    if (session()->has('locale')) {
        $locale = session('locale');
        
        // ثلاث طبقات حماية لتطبيق اللغة
        app()->setLocale($locale);
        config(['app.locale' => $locale]);
        Carbon::setLocale($locale);
        
        // تجاوز كاش اللغة
        $this->app['translator']->setLocale($locale);
    }
}
      /**
       * Configure the rate limiters for the application.
       */
      protected function configureRateLimiting(): void
      {
          RateLimiter::for('api', function (Request $request) {
              Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
          });
      }
  }