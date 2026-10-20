<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'manager',
            'receptionist',
            'waiter',
            'kitchen_chef',
            'delivery_boy',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = [
            'view dashboard',
            'manage categories',
            'manage items',
            'manage rooms',
            'manage inventory',
            'manage banners',
            'manage orders',
            'manage table bookings',
            'manage room bookings',
            'manage users',
            'manage expenses',
            'manage salaries',
            'manage holidays',
            'generate reports',
            'use pos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::where('name', 'manager')->first();
        $managerRole->givePermissionTo([
            'view dashboard',
            'manage categories',
            'manage items',
            'manage rooms',
            'manage inventory',
            'manage banners',
            'manage orders',
            'manage table bookings',
            'manage room bookings',
            'manage users',
            'manage expenses',
            'manage salaries',
            'manage holidays',
            'generate reports',
            'use pos',
        ]);

        $receptionistRole = Role::where('name', 'receptionist')->first();
        $receptionistRole->givePermissionTo([
            'view dashboard',
            'manage room bookings',
            'manage table bookings',
            'use pos',
        ]);

        $waiterRole = Role::where('name', 'waiter')->first();
        $waiterRole->givePermissionTo([
            'view dashboard',
            'manage orders',
            'use pos',
        ]);

        $chefRole = Role::where('name', 'kitchen_chef')->first();
        $chefRole->givePermissionTo([
            'view dashboard',
            'manage orders',
        ]);

        $deliveryBoyRole = Role::where('name', 'delivery_boy')->first();
        $deliveryBoyRole->givePermissionTo([
            'view dashboard',
            'manage orders',
        ]);
    }
}
