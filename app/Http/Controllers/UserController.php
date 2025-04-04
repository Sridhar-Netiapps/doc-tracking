<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Constructor for middleware
    // public function __construct()
    // {
    //     // Add the permission middleware as needed for each method
    //     // Example:
    //     // $this->middleware('permission:view-user')->only('index','show');
    //     // $this->middleware('permission:create-user')->only(['create', 'store']);
    //     // $this->middleware('permission:edit-user')->only(['edit', 'update']);
    //     // $this->middleware('permission:delete-user')->only('destroy');
    // }

    // List all users
    public function index()
    {
        // Fetching all users and eager loading their roles and permissions
        $users = User::with('roles', 'permissions')->get();

        // Passing the users data to the view
        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        // Returning the view for creating a user
        return view('users.create');
    }

    // Store a new user in the database
    public function store(Request $request)
    {
        // Validating the input data
        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'status' => 'required|string|max:10',
            'mobile_number' => 'required|string|max:15',
            'doj' => 'required|date',
            'dor' => 'nullable|date',
        ]);

        // Creating the new user
        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'password' => Hash::make('password'),
            'employee_id' => $request->input('employee_id'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'dob' => $request->input('dob'),
            'status' => $request->input('status'),
            'mobile_number' => $request->input('mobile_number'),
            'doj' => $request->input('doj'),
            'dor' => $request->input('dor'),
        ]);

        // Redirecting back with success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show the form for editing a user
    public function edit(User $user)
    {
        // Fetching roles and permissions for the user
        $roles = Role::all();
        $permissions = Permission::all();

        // Passing the user, roles, and permissions to the edit view
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    // Update the specified user in the database
    public function update(Request $request, User $user)
    {
        // Validating the updated user data
        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:users,employee_id,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'status' => 'required|string|max:10',
            'mobile_number' => 'required|string|max:15',
            'doj' => 'required|date',
            'dor' => 'nullable|date',

        ]);

        // Updating the user
        $user->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'employee_id' => $request->input('employee_id'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'dob' => $request->input('dob'),
            'status' => $request->input('status'),
            'mobile_number' => $request->input('mobile_number'),
            'doj' => $request->input('doj'),
            'dor' => $request->input('dor'),
            
        ]);

        // Redirecting back with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete the specified user from the database
    public function destroy(User $user)
    {
        // Deleting the user
        $user->delete();

        // Redirecting back with success message
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // // Assign a role to a user
    // public function assignRole(Request $request, User $user)
    // {
    //     // Validating the role input
    //     $request->validate([
    //         'role' => 'required|exists:roles,name',
    //     ]);

    //     // Syncing the role with the user
    //     $user->syncRoles([$request->input('role')]);

    //     // Redirecting back with success message
    //     return redirect()->back()->with('success', 'Role assigned successfully.');
    // }

    // // Assign a permission to a user
    // public function assignPermission(Request $request, User $user)
    // {
    //     // Validating the permission input
    //     $request->validate([
    //         'permission' => 'required|exists:permissions,name',
    //     ]);

    //     // Giving permission to the user
    //     $user->givePermissionTo($request->input('permission'));

    //     // Redirecting back with success message
    //     return redirect()->back()->with('success', 'Permission assigned successfully.');
    // }
}
