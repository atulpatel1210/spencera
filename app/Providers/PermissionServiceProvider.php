<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Execute only if tables exist to prevent errors during migrations
        if ($this->app->runningInConsole() || !Schema::hasTable(config('permission.table_names.permissions'))) {
            return;
        }

        $this->autoGeneratePermissions();
    }

    private function autoGeneratePermissions()
    {
        $routes = Route::getRoutes()->getRoutes();
        $existingPermissions = Permission::pluck('name')->toArray();
        $newPermissions = [];

        foreach ($routes as $route) {
            $routeName = $route->getName();
            
            // Generate permissions only for specific modules usually named like: parties.index, users.create, etc.
            if ($routeName && $this->isModuleRoute($routeName)) {
                $permissionName = $this->formatPermissionName($routeName);
                
                if (!in_array($permissionName, $existingPermissions) && !in_array($permissionName, $newPermissions)) {
                    $newPermissions[] = $permissionName;
                }
            }
        }

        // Insert new permissions
        foreach ($newPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            
            // Optional: Automatically assign to Admin role if exists
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
        }
    }

    private function isModuleRoute($routeName)
    {
        $ignoredPrefixes = ['ignition', 'sanctum', 'api', 'login', 'logout', 'password', 'register', 'verification', 'profile', 'dashboard', 'storage', 'parties.data'];
        
        foreach ($ignoredPrefixes as $prefix) {
            if (Str::startsWith($routeName, $prefix)) {
                return false;
            }
        }

        // Must have a dot like module.action
        return strpos($routeName, '.') !== false;
    }

    private function formatPermissionName($routeName)
    {
        // Examples: 
        // parties.index -> "view parties"
        // parties.create / parties.store -> "create parties"
        // parties.edit / parties.update -> "edit parties"
        // parties.destroy -> "delete parties"
        
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
        
        // Clean module name: substitute underscores with spaces. E.g purchase_order_pallets -> purchase order pallets
        $module = str_replace('_', ' ', $module);

        return trim("$mappedAction $module");
    }
}
