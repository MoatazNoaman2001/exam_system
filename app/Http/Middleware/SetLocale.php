<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
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
        // Debug: Log the current route
        Log::info('SetLocale middleware - Route: ' . $request->getPathInfo());
        Log::info('SetLocale middleware - Method: ' . $request->getMethod());
        
        // Get locale from various sources
        $locale = $this->getLocale($request);
        
        // Validate and set locale
        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            App::setLocale('en');
            Session::put('locale', 'en');
        }
        
        // Continue with the request and get response
        $response = $next($request);
        
        // Debug: Check what type of response we got
        Log::info('SetLocale middleware - Response type: ' . gettype($response));
        if (is_object($response)) {
            Log::info('SetLocale middleware - Response class: ' . get_class($response));
        }
        if (is_array($response)) {
            Log::info('SetLocale middleware - Response array keys: ' . implode(', ', array_keys($response)));
        }
        
        // Handle different response types
        if (!$response instanceof Response) {
            Log::warning('SetLocale middleware - Converting non-Response to Response');
            
            if (is_array($response) || is_object($response)) {
                // This might be JSON API response
                return response()->json($response);
            } else {
                // Convert to string response
                return response((string) $response);
            }
        }
        
        // Add locale to cookie for future requests
        try {
            if (method_exists($response, 'withCookie')) {
                $response->withCookie(cookie('locale', App::getLocale(), 60 * 24 * 365));
            }
        } catch (\Exception $e) {
            Log::warning('SetLocale middleware - Cookie error: ' . $e->getMessage());
        }
        
        return $response;
    }

    /**
     * Get locale from various sources in order of priority
     */
    private function getLocale(Request $request): string
    {
        // 1. Check for locale in URL parameter (for language switching)
        if ($request->has('locale')) {
            return $request->get('locale');
        }
        
        // 2. Check for locale in session
        if (Session::has('locale')) {
            return Session::get('locale');
        }
        
        // 3. Check for locale in cookie
        if ($request->hasCookie('locale')) {
            return $request->cookie('locale');
        }
        
        // 4. Use browser preference or default
        return $this->getPreferredLocale($request);
    }

    /**
     * Get preferred locale from browser headers
     */
    private function getPreferredLocale(Request $request): string
    {
        try {
            $browserLocale = $request->getPreferredLanguage(['en', 'ar']);
            return $browserLocale ?: 'en';
        } catch (\Exception $e) {
            return 'en';
        }
    }
}