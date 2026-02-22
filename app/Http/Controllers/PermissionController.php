<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function syncPermissions()
    {
        $routes = Route::getRoutes()->getRoutes();
        $existingPermissions = Permission::pluck('name')->toArray();
        $newPermissions = [];
        $activeModules = [];

        foreach ($routes as $route) {
            $routeName = $route->getName();
            
            if ($routeName && $this->isModuleRoute($routeName)) {
                $permissionName = $this->formatPermissionName($routeName);
                $activeModules[] = $permissionName;
                
                if (!in_array($permissionName, $existingPermissions) && !in_array($permissionName, $newPermissions)) {
                    $newPermissions[] = $permissionName;
                }
            }
        }

        // Insert new permissions
        $addedCount = 0;
        foreach ($newPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            
            // Assign to admin
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
            $addedCount++;
        }

        // Remove unused/old permissions that no longer exist in routes
        $activeModules = array_unique($activeModules);
        $deletedCount = 0;
        
        // Exclude manual/default ones you always want to keep
        $keepPermissions = [
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view users', 'create users', 'edit users', 'delete users',
            'view permissions'
        ];
        
        $protect = array_merge($activeModules, $keepPermissions);
        
        $permissionsToDelete = Permission::whereNotIn('name', $protect)->get();
        
        foreach ($permissionsToDelete as $permToDelete) {
            $permToDelete->delete();
            $deletedCount++;
        }

        return redirect()->back()->with('success', "Permissions synced successfully! Added: $addedCount, Removed: $deletedCount");
    }

    private function isModuleRoute($routeName)
    {
        $ignoredPrefixes = ['ignition', 'sanctum', 'api', 'login', 'logout', 'password', 'register', 'verification', 'profile', 'dashboard', 'storage', 'parties.data'];
        
        foreach ($ignoredPrefixes as $prefix) {
            if (Str::startsWith($routeName, $prefix)) {
                return false;
            }
        }

        return strpos($routeName, '.') !== false;
    }

    private function formatPermissionName($routeName)
    {
        $parts = explode('.', $routeName);
        if (count($parts) < 2) return $routeName;

        $module = $parts[0];
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
        $module = str_replace('_', ' ', $module);

        return trim("$mappedAction $module");
    }
}
