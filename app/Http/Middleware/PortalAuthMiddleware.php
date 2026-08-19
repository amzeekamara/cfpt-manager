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
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à votre espace.');
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        if ($type === 'candidate' && (!$user->role || $user->role->name !== 'Candidat')) {
            return redirect()->route('login')->with('error', 'Accès réservé aux candidats.');
        }

        if ($type === 'trainer') {
            if (!$user->role || $user->role->name !== 'Formateur') {
                return redirect()->route('login')->with('error', 'Accès réservé aux formateurs.');
            }

            if (!$request->session()->has('portal_trainer_id')) {
                $trainer = \App\Models\Trainer::where('email', $user->email)->first();
                if ($trainer) {
                    $request->session()->put('portal_trainer_id', $trainer->id);
                } else {
                    return redirect()->route('login')->with('error', 'Aucun profil de formateur associé à ce compte.');
                }
            }
        }

        return $next($request);
    }
}
