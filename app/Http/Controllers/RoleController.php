<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // List all roles
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Display the role creation form with permissions
    public function create()
    {
        // Get all available permissions
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Store a newly created role and assign permissions
    public function store(Request $request)
    {
        // Validate role name
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array', // Permissions must be an array
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);

        // Assign the selected permissions to the role
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // Display the role editing form with permissions
    public function edit($id)
    {
        // Find the role and get all permissions
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        // Get the permissions assigned to this role
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update the role and sync permissions
    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array', // Permissions must be an array
        ]);

        // Find the role
        $role = Role::findOrFail($id);

        // Update the role name
        $role->name = $request->name;
        $role->save();

        // Sync the selected permissions with the role
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    // Delete a role
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
