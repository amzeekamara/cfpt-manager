<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictJica
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Si l'utilisateur est un Agent JICA, on vérifie l'accès
        if ($user && $user->role && $user->role->name === 'Agent JICA') {
            
            // On bloque par défaut toute requête qui n'est pas une requête de lecture simple
            // Sauf si c'est explicitement permis (comme une route POST d'export si elle existe)
            if (!in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
                abort(403, 'Accès en lecture seule. Vous ne pouvez pas modifier ces données.');
            }

            // Liste des noms de routes autorisées (préfixes)
            $allowedPrefixes = [
                'admin.dashboard',
                'admin.cohorts.index',
                'admin.seminars', // Autorise admin.seminars (index) mais on bloquera les create/edit ci-dessous
                'admin.topics.index',
                'admin.departments.index',
                'admin.finances', // Index et export
                'admin.notifications.index'
            ];

            $routeName = $request->route()->getName();
            
            // Si le nom de route est null, on laisse passer (bien que rare dans les routes admin nommées)
            if (!$routeName) {
                return $next($request);
            }

            // Bloquer spécifiquement les pages de création/édition
            $blockedActions = ['.create', '.edit', '.store', '.update', '.destroy', '.toggle'];
            foreach ($blockedActions as $action) {
                if (str_ends_with($routeName, $action)) {
                    abort(403, 'Vous ne pouvez pas accéder aux interfaces de modification.');
                }
            }

            $isAllowed = false;
            foreach ($allowedPrefixes as $prefix) {
                if (str_starts_with($routeName, $prefix)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                abort(403, 'Accès non autorisé à ce module pour votre profil.');
            }
        }

        return $next($request);
    }
}
