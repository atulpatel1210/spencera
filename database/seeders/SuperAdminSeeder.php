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
        
        // Notes: Superadmin permissions are now handled implicitly via Gate::before 
        // in AppServiceProvider. This prevents issues during fresh setups where
        // the permissions table might be empty initially.

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
