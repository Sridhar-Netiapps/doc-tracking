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

        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'doc-dispatch']);
        Permission::create(['name' => 'doc-update']);

        $role = Role::create(['name' => 'master']);
        $role->givePermissionTo(Permission::all());
        
        $role = Role::create(['name' => 'super_admin']);
        $role->givePermissionTo(Permission::all());
        
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'bank-user']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'ro-user']);
        $role->givePermissionTo('doc-update');

        $role = Role::create(['name' => 'bo-maker']);
        $role->givePermissionTo('doc-dispatch');

        $role = Role::create(['name' => 'bo-checker']);
        $role->givePermissionTo('doc-dispatch');

        $users = User::factory(31)->create();

        foreach ($users as $index => $user) {
            if ($index <= 1) {
                $user->assignRole('master'); // First user gets Master
            } elseif ($index <= 3) {
                $user->assignRole('super_admin'); // Next 3 users
            } elseif ($index <= 5) {
                $user->assignRole('admin'); // Next 3 users
            }elseif ($index <= 7) {
                $user->assignRole('bank-user'); // Next 3 users
            }elseif ($index <= 12) {
                $user->assignRole('ro-user'); // Next 3 users
            }elseif ($index <= 21) {
                $user->assignRole('bo-maker'); // Next 3 users
            }elseif ($index <= 30) {
                $user->assignRole('bo-checker'); // Next 3 users
            }else {
                $user->assignRole('mail-room'); // Remaining users
            }
        }
    }
}
