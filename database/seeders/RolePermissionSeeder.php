<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'Manager',
            'Receptionist',
            'Waiter',
            'Kitchen Chef',
            'Delivery Boy',
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
            'generate reports',
            'use pos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::where('name', 'Manager')->first();
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
            'generate reports',
            'use pos',
        ]);

        $receptionistRole = Role::where('name', 'Receptionist')->first();
        $receptionistRole->givePermissionTo([
            'view dashboard',
            'manage room bookings',
            'manage table bookings',
            'use pos',
        ]);

        $waiterRole = Role::where('name', 'Waiter')->first();
        $waiterRole->givePermissionTo([
            'view dashboard',
            'manage orders',
            'use pos',
        ]);

        $chefRole = Role::where('name', 'Kitchen Chef')->first();
        $chefRole->givePermissionTo([
            'view dashboard',
            'manage orders',
        ]);

        $deliveryBoyRole = Role::where('name', 'Delivery Boy')->first();
        $deliveryBoyRole->givePermissionTo([
            'view dashboard',
            'manage orders',
        ]);
    }
}
