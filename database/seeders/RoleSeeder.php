<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Platform-level role
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Restaurant management roles
        Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'chef',    'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'cajero',  'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'mesero',  'guard_name' => 'web']);

        // End-user role
        Role::firstOrCreate(['name' => 'cliente', 'guard_name' => 'web']);

        $this->seedPermissions();
    }

    private function seedPermissions(): void
    {
        $permissions = [
            // Restaurant management
            'restaurant.view', 'restaurant.edit',

            // Menu
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',

            // Inventory
            'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',

            // Orders
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete',

            // Tables
            'tables.view', 'tables.create', 'tables.edit', 'tables.delete',

            // Staff
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete',

            // Finance (tips + expenses)
            'finance.view', 'finance.create', 'finance.edit',

            // AI
            'ai.view', 'ai.use',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign all permissions to super_admin and admin
        $superAdmin = Role::findByName('super_admin');
        $admin      = Role::findByName('admin');
        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::all());

        // Chef: menu read + orders view/create
        Role::findByName('chef')->syncPermissions([
            'menu.view', 'orders.view', 'orders.edit', 'inventory.view',
        ]);

        // Cajero: orders + finance
        Role::findByName('cajero')->syncPermissions([
            'orders.view', 'orders.create', 'orders.edit',
            'finance.view', 'finance.create', 'tables.view',
        ]);

        // Mesero: orders + tables
        Role::findByName('mesero')->syncPermissions([
            'orders.view', 'orders.create', 'orders.edit',
            'tables.view', 'tables.edit', 'menu.view',
        ]);
    }
}
