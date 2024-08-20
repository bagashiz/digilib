<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect(route('login'));
        }

        $user = auth()->user();
        if (!$user || $user->role !== UserRole::MEMBER) {
            return redirect(route('home'));
        }

        return $next($request);
    }
}
