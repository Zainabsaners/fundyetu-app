<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions (use firstOrCreate to avoid duplicates)
        $permissions = [
            'manage campaigns',
            'manage users',
            'manage withdrawals',
            'verify documents',
            'view analytics',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Create roles (firstOrCreate)
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(['manage campaigns', 'manage users', 'manage withdrawals', 'verify documents', 'view analytics']);

        Role::firstOrCreate(['name' => 'fundraiser', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'donor', 'guard_name' => 'web']);
    }
}