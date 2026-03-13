<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // HSTS (Strict-Transport-Security) - only for production and HTTPS
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options (Clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection (Legacy but good for some older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy (Basic - adjust as needed for frontend assets)
        // Note: For SPAs, you might need to allow 'unsafe-inline' or 'unsafe-eval' depending on your build tool.
        // We'll start conservative and adjust if frontend breaks.
        $csp = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.paystack.co; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self' https://api.paystack.co https://api.openai.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}