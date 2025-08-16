<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Debug: Log the current route
            Log::info('SetLocale middleware - Route: ' . $request->getPathInfo());
            Log::info('SetLocale middleware - Method: ' . $request->getMethod());
            
            // Get locale from various sources
            $locale = $this->getLocale($request);
            
            // Validate and set locale
            if (in_array($locale, ['en', 'ar'])) {
                App::setLocale($locale);
                Session::put('locale', $locale);
                Log::info('SetLocale middleware - Locale set to: ' . $locale);
            } else {
                App::setLocale('en');
                Session::put('locale', 'en');
                Log::info('SetLocale middleware - Invalid locale, defaulting to: en');
            }
            
            // Continue with the request and get response
            $response = $next($request);
            
            // Debug: Check what type of response we got
            Log::info('SetLocale middleware - Response type: ' . gettype($response));
            
            if (is_object($response)) {
                Log::info('SetLocale middleware - Response class: ' . get_class($response));
            }
            
            if (is_array($response)) {
                Log::info('SetLocale middleware - Response is array with keys: ' . implode(', ', array_keys($response)));
                // Convert array to JSON response
                return response()->json($response);
            }
            
            if (is_string($response)) {
                Log::info('SetLocale middleware - Response is string, converting to Response');
                return response($response);
            }
            
            if (is_null($response)) {
                Log::warning('SetLocale middleware - Response is null, creating empty response');
                return response('', 204); // No Content
            }
            
            // Check if it's a valid response object
            if (!$response instanceof Response) {
                Log::error('SetLocale middleware - Invalid response type: ' . gettype($response));
                
                // Try to convert to proper response
                if (is_callable([$response, 'toResponse'])) {
                    $response = $response->toResponse($request);
                } else {
                    // Last resort - create a generic response
                    Log::error('SetLocale middleware - Cannot convert response, creating generic 500 response');
                    return response('Internal Server Error', 500);
                }
            }
            
            // Verify we now have a proper Response
            if (!$response instanceof Response) {
                Log::error('SetLocale middleware - Still not a Response after conversion attempts');
                return response('Internal Server Error', 500);
            }
            
            // Add locale to cookie for future requests
            try {
                if ($response instanceof JsonResponse || 
                    $response instanceof RedirectResponse || 
                    method_exists($response, 'withCookie')) {
                    
                    $response->withCookie(
                        cookie('locale', App::getLocale(), 60 * 24 * 365, '/', null, false, false)
                    );
                }
            } catch (\Exception $e) {
                Log::warning('SetLocale middleware - Cookie error: ' . $e->getMessage());
            }
            
            Log::info('SetLocale middleware - Successfully processed, returning response');
            return $response;
            
        } catch (\Exception $e) {
            Log::error('SetLocale middleware - Exception: ' . $e->getMessage());
            Log::error('SetLocale middleware - Stack trace: ' . $e->getTraceAsString());
            
            // Set default locale and continue
            App::setLocale('en');
            Session::put('locale', 'en');
            
            // Try to continue with the request
            try {
                $response = $next($request);
                
                if ($response instanceof Response) {
                    return $response;
                } else {
                    return response('Internal Server Error', 500);
                }
            } catch (\Exception $innerException) {
                Log::error('SetLocale middleware - Inner exception: ' . $innerException->getMessage());
                return response('Internal Server Error', 500);
            }
        }
    }

    /**
     * Get locale from various sources in order of priority
     */
    private function getLocale(Request $request): string
    {
        try {
            // 1. Check for locale in URL parameter (for language switching)
            if ($request->has('locale')) {
                $locale = $request->get('locale');
                Log::info('SetLocale - Found locale in URL parameter: ' . $locale);
                return $locale;
            }
            
            // 2. Check for locale in session
            if (Session::has('locale')) {
                $locale = Session::get('locale');
                Log::info('SetLocale - Found locale in session: ' . $locale);
                return $locale;
            }
            
            // 3. Check for locale in cookie
            if ($request->hasCookie('locale')) {
                $locale = $request->cookie('locale');
                Log::info('SetLocale - Found locale in cookie: ' . $locale);
                return $locale;
            }
            
            // 4. Use browser preference or default
            $locale = $this->getPreferredLocale($request);
            Log::info('SetLocale - Using preferred/default locale: ' . $locale);
            return $locale;
            
        } catch (\Exception $e) {
            Log::error('SetLocale - Error getting locale: ' . $e->getMessage());
            return 'en';
        }
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
            Log::warning('SetLocale - Error getting browser locale: ' . $e->getMessage());
            return 'en';
        }
    }
}