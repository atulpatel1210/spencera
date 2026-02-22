<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckActionPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request); // Let auth middleware handle non-logged-in users
        }

        // Super Admin bypass
        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        $routeName = $request->route()->getName();
        if (!$routeName) {
            return $next($request);
        }

        // Generate expected permission name exactly like the provider
        $permissionName = $this->getPermissionName($routeName);
        
        if ($permissionName && !$user->can($permissionName)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'You do not have permission to perform this action.'], 403);
            }
            abort(403, 'Unauthorized action. You do not have permission to access ' . $permissionName);
        }

        return $next($request);
    }

    private function getPermissionName($routeName)
    {
        $ignoredPrefixes = ['ignition', 'sanctum', 'api', 'login', 'logout', 'password', 'register', 'verification', 'profile', 'dashboard', 'storage', 'parties.data'];
        
        foreach ($ignoredPrefixes as $prefix) {
            if (\Illuminate\Support\Str::startsWith($routeName, $prefix)) {
                return null; // No permission checking for these
            }
        }

        if (strpos($routeName, '.') === false) return null;

        $parts = explode('.', $routeName);
        $module = str_replace('_', ' ', $parts[0]);
        $action = last($parts);

        $actionMap = [
            'index' => 'view',
            'show' => 'view',
            'data' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'edit',
            'update' => 'edit',
            'destroy' => 'delete',
            'import' => 'create',
            'form' => 'create',
            'report' => 'view'
        ];

        $mappedAction = $actionMap[$action] ?? $action;

        return trim("$mappedAction $module");
    }
}
