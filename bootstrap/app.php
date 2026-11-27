<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\TrackPageViews::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // An expired session (page left open past SESSION_LIFETIME) makes the CSRF token stale.
        // Instead of Laravel's bare "419 Page Expired" page, finish the user's intent where safe.
        $exceptions->render(function (HttpException $e, Request $request) {
            // Laravel turns TokenMismatchException into a 419 HttpException before render callbacks run.
            if ($e->getStatusCode() !== 419 || ! $e->getPrevious() instanceof TokenMismatchException || $request->expectsJson()) {
                return null;
            }

            // Logging out with a stale token: the session is already gone or about to be, so just end it.
            if ($request->routeIs("logout")) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route("login")->with("status", __("Anda sudah keluar."));
            }

            return redirect()->back()
                ->withInput($request->except(["password", "password_confirmation", "current_password", "_token"]))
                ->withErrors(["session" => __("Sesi Anda kedaluwarsa. Silakan kirim ulang formulir.")]);
        });
    })->create();

$app->usePublicPath(
    is_dir($app->basePath('public'))
        ? $app->basePath('public')
        : $app->basePath('public_html')
);

return $app;
