<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Superadmin']);
        
        // Give Superadmin all permissions via explicit permission assigning, or ideally they bypass it in middleware
        $permissions = \Spatie\Permission\Models\Permission::all();
        $superAdminRole->syncPermissions($permissions);

        $superAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'System Master',
                'password' => \Illuminate\Support\Facades\Hash::make('superadmin123'),
                'company_id' => null, // Superadmin doesn't belong to any specific company
            ]
        );
        
        $superAdmin->assignRole('Superadmin');
    }
}
