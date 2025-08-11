<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        
        
        Permission::firstOrCreate(['name' => 'manage products']);
        Permission::firstOrCreate(['name' => 'manage orders']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'view assigned tasks']);
        Permission::firstOrCreate(['name' => 'view own orders']);
        
        
        
        
        $technicianRole = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);
        $technicianRole->givePermissionTo('view assigned tasks');

        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customerRole->givePermissionTo('view own orders');
        
        $superAdminRole = Role::firstOrCreate(['name' => 'Super-Admin', 'guard_name' => 'admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        $orderManagerRole = Role::firstOrCreate(['name' => 'order-manager', 'guard_name' => 'admin']);
        $orderManagerRole->givePermissionTo('manage orders');
    }
}