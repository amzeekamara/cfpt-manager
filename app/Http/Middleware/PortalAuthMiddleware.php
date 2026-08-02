<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortalAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if ($type === 'candidate' && !$request->session()->has('portal_candidate_id')) {
            return redirect()->route('portal.candidate.login')->with('error', 'Veuillez vous connecter pour accéder à votre espace.');
        }

        if ($type === 'trainer' && !$request->session()->has('portal_trainer_id')) {
            return redirect()->route('portal.trainer.login')->with('error', 'Veuillez vous connecter pour accéder à votre espace.');
        }

        return $next($request);
    }
}
