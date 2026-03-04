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

        // Permission::create(['name' => 'create-user']);
        // Permission::create(['name' => 'edit-user']);
        // Permission::create(['name' => 'view-user']);
        // Permission::create(['name' => 'delete-user']);
        // Permission::create(['name' => 'doc-dispatch']);
        // Permission::create(['name' => 'doc-update']);

        // $role = Role::create(['name' => 'master']);
        // $role->givePermissionTo(Permission::all());
        
        // $role = Role::create(['name' => 'super_admin']);
        // $role->givePermissionTo(Permission::all());
        
        // $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo(Permission::all());

        // $role = Role::create(['name' => 'ho-user']);
        // $role->givePermissionTo(Permission::all());

        // $role = Role::create(['name' => 'ro-officer']);
        // $role->givePermissionTo('doc-update');

        // $role = Role::create(['name' => 'bo-maker']);
        // $role->givePermissionTo('doc-dispatch');

        // $role = Role::create(['name' => 'bo-checker']);
        // $role->givePermissionTo('doc-dispatch');

        $users = User::factory(500)->create();

        foreach ($users as $index => $user) {
            if ($index <= 1) {
                $user->assignRole('master'); // First user gets Master
            } elseif ($index <= 3) {
                $user->assignRole('super_admin'); // Next 3 users
            } elseif ($index <= 5) {
                $user->assignRole('admin'); // Next 3 users
            }elseif ($index <= 7) {
                $user->assignRole('ho-user'); // Next 3 users
            }elseif ($index <= 12) {
                $user->assignRole('ro-officer'); // Next 3 users
            }elseif ($index <= 21) {
                $user->assignRole('bo-maker'); // Next 3 users
            }elseif ($index <= 30) {
                $user->assignRole('bo-checker'); // Next 3 users
            }
            // else {
            //     $user->assignRole('mail-room'); // Remaining users
            // }
        }
    }
}

/*master - netiapps
super_admin - iT admin
admin - new_user 

bo-maker
bo-checker

ro-officer
ro-supervisor

ho-user

branch-user --readonly
ro-user --readonly
*/


