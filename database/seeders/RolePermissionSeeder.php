<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Add default permissions
        $permissions = [
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view users', 'create users', 'edit users', 'delete users',
            'view permissions'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // Create user role (with limited permissions, or none by default)
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Create an Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // password is 'password'
            ]
        );
        $adminUser->assignRole($adminRole);

        // Since this project might be multitenant logically without a specific structure,
        // we keep the role setup simple enough so any company using instances can manage roles out of the box.
    }
}
