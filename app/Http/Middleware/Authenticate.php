<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Closure;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an unauthenticated user and redirect to login if necessary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (AuthenticationException $exception) {
            return redirect()->route('login')->with('error', 'セッションが切れました。再度ログインしてください。');
        }
    }
}
