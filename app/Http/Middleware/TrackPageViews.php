<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isBot = (bool) preg_match('/bot|crawl|spider|slurp|curl|wget|python|headless|lighthouse|monitor/i', (string) $request->userAgent());

        if ($request->isMethod('GET')
            && $response->getStatusCode() === 200
            && ! $isBot
            && ! $request->ajax()
            && ! $request->expectsJson()
            && ! str_starts_with($request->path(), 'admin')
            && ! str_starts_with($request->path(), '_')
            // Client invoice links carry a secret token; never store them.
            && ! str_starts_with($request->path(), 'invoice/')
            && ! in_array($request->path(), ['up', 'sitemap.xml', 'robots.txt'], true)) {
            try {
                PageView::create([
                    'url' => $request->path(),
                    'title' => $request->route()?->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Exception $e) {
                // silently fail
            }
        }

        return $response;
    }
}
