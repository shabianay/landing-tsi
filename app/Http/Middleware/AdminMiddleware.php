<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized access.');
        }

        // Checked once per session (bcrypt is slow); the layout shows a warning while it is true.
        if (! $request->session()->has('admin_weak_password')) {
            $request->session()->put('admin_weak_password', Hash::check('password', auth()->user()->password));
        }

        return $next($request);
    }
}
