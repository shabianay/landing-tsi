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

        if ($request->isMethod('GET') && !$request->ajax() && !$request->expectsJson() && !str_starts_with($request->path(), 'admin') && !str_starts_with($request->path(), '_') && $request->path() !== 'up') {
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
