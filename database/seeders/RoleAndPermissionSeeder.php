<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'user:create',
            'user:update',
            'user:replace',
            'user:delete',

            'product:list',
            'product:create',
            'product:update',
            'product:replace',
            'product:delete',

            'category:create',
            'category:update',
            'category:replace',
            'category:delete',

            'banner:create',
            'banner:update',
            'banner:replace',
            'banner:delete',

            'order:create',
            'order:update',
            'order:replace',
            'order:delete',

            'role:create',
            'role:update',
            'role:replace',
            'role:delete',

            'permission:create',
            'permission:update',
            'permission:replace',
            'permission:delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);

        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(array_diff(
            $permissions, 
            [
                'role:create',
                'role:update',
                'role:replace',
                'role:delete',
                'permission:create',
                'permission:update',
                'permission:replace',
                'permission:delete',
            ]
        ));
    }
}
