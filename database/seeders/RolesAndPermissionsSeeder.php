<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'create-device']);
        Permission::create(['name' => 'edit-device']);
        Permission::create(['name' => 'view-device']);
        Permission::create(['name' => 'delete-device']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'pair-device']);
        Permission::create(['name' => 'unpair-device']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'master']);
        $role->givePermissionTo(Permission::all()); // Admin gets all permissions

        $role = Role::create(['name' => 'super_admin']);
        $role->givePermissionTo('edit-device');
        $role->givePermissionTo('pair-device');
        $role->givePermissionTo('unpair-device');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('pair-device');
        $role->givePermissionTo('unpair-device');

        $users = User::factory(10)->create();

        foreach ($users as $index => $user) {
            if ($index === 0) {
                $user->assignRole('master'); // First user gets Master
            } elseif ($index <= 3) {
                $user->assignRole('super_admin'); // Next 3 users get Super Admin
            } else {
                $user->assignRole('admin'); // Remaining users get Admin
            }
        }
    }
}
