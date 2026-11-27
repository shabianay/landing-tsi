<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * CSRF verification is skipped while running unit tests, so these tests hand a
 * TokenMismatchException straight to the exception handler, as the middleware would.
 */
class ExpiredSessionTest extends TestCase
{
    use RefreshDatabase;

    private function renderTokenMismatch(string $method, string $uri, array $input = [], ?string $referer = null)
    {
        $request = Request::create($uri, $method, $input, server: $referer ? ['HTTP_REFERER' => $referer] : []);
        $request->setLaravelSession($this->app['session.store']);
        $request->setRouteResolver(fn () => $this->app['router']->getRoutes()->match($request));
        $this->app->instance('request', $request);

        return $this->app->make(ExceptionHandler::class)->render($request, new TokenMismatchException('CSRF token mismatch.'));
    }

    public function test_logout_with_a_stale_csrf_token_still_signs_out_instead_of_419(): void
    {
        Auth::login(User::factory()->create(['is_admin' => true]));

        $response = $this->renderTokenMismatch('POST', route('logout'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('login'), $response->headers->get('Location'));
        $this->assertGuest();
    }

    public function test_other_forms_with_a_stale_token_go_back_with_a_message_and_input(): void
    {
        $response = $this->renderTokenMismatch('POST', route('contact.store'), ['name' => 'Budi', 'password' => 'secret'], route('contact'));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('contact'), $response->headers->get('Location'));
        $this->assertTrue(session('errors')->has('session'));
        $this->assertSame('Budi', session('_old_input.name'));
        $this->assertNull(session('_old_input.password'));
    }
}
