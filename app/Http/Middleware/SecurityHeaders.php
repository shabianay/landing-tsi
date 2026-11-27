<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // Every script tag rendered by @vite gets this nonce; inline scripts without it are blocked.
        $nonce = Vite::useCspNonce();

        $response = $next($request);

        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($nonce));
        $response->headers->remove('X-Powered-By');
        if (! headers_sent()) {
            header_remove('X-Powered-By');
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }

    private function contentSecurityPolicy(string $nonce): string
    {
        $recaptcha = 'https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/';
        $dev = $this->viteDevServer();
        $devWs = $dev ? preg_replace('#^http#', 'ws', $dev) : '';

        $directives = [
            'default-src' => "'self'",
            // 'unsafe-eval' is required by Alpine.js (standard build evaluates x-* expressions).
            'script-src' => trim("'self' 'nonce-{$nonce}' 'unsafe-eval' {$recaptcha} {$dev}"),
            'style-src' => trim("'self' 'unsafe-inline' https://fonts.googleapis.com https://api.fontshare.com https://fonts.bunny.net {$dev}"),
            'font-src' => "'self' data: https://fonts.gstatic.com https://cdn.fontshare.com https://api.fontshare.com https://fonts.bunny.net",
            'img-src' => "'self' data: blob: https:",
            'connect-src' => trim("'self' {$dev} {$devWs}"),
            'frame-src' => 'https://www.google.com/recaptcha/ https://recaptcha.google.com/recaptcha/',
            'object-src' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'frame-ancestors' => "'self'",
        ];

        if (app()->isProduction()) {
            $directives['upgrade-insecure-requests'] = '';
        }

        return collect($directives)
            ->map(fn (string $value, string $name) => trim("{$name} {$value}"))
            ->implode('; ');
    }

    /** Origin of the local Vite dev server (public/hot), only when it is running. */
    private function viteDevServer(): string
    {
        if (app()->isProduction() || ! Vite::isRunningHot()) {
            return '';
        }

        $url = trim((string) @file_get_contents(public_path('hot')));
        $parts = parse_url($url);

        return isset($parts['scheme'], $parts['host'])
            ? $parts['scheme'].'://'.$parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '')
            : '';
    }
}
