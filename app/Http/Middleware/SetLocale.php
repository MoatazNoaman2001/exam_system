<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for locale in session first
        $locale = Session::get('locale');
        
        // If not in session, check cookie
        if (!$locale) {
            $locale = $request->cookie('locale');
        }
        
        // If still no locale, use browser preference or default
        if (!$locale) {
            $locale = $this->getPreferredLocale($request);
        }
        
        // Validate and set locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }

    /**
     * Get preferred locale from browser headers
     */
    private function getPreferredLocale(Request $request)
    {
        $browserLocale = $request->getPreferredLanguage(['en', 'ar']);
        return $browserLocale ?: 'en'; // Default to English
    }
}
