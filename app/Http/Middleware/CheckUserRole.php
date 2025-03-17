<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        $userHasRole = $user->hasRole($role);

        if (!$userHasRole) {
            $message = 'Access denied. You don´t have permissions to do that';
            $routeIsApi = $this->isApi($request);

            return $routeIsApi
                ? response()->json([
                    'message' => $message
                ], 403)
                : redirect()
                ->route('indexFlight', [], 303) // Se usa el 303 ya que se redirige y se evita que se vuelva a enviar
                ->with('message', $message);
        }

        return $next($request);
    }

    private function isApi(Request $request)
    {
        return $request->is('api/*');
    }
}
