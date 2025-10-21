<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerScopeMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware ensures that organizers only see their own data
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user || !$user->isOrganizer()) {
            abort(403, 'Only organizers can access this resource');
        }

        // Store organizer ID in request for easy access in controllers
        $request->attributes->set('organizer_id', $user->id);
        
        return $next($request);
    }
}
