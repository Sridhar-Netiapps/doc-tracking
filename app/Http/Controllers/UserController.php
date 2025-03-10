<?php
namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view-user')->only('index','show');
        // $this->middleware('permission:create-user')->only(['create', 'store']);
        // $this->middleware('permission:edit-user')->only(['edit', 'update']);
        // $this->middleware('permission:delete-user')->only('destroy');
    }

    // List all users
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        // dd($users->roles);
        return view('users.index', compact('users'));
    }


    // public function create()
    // {
    //     throw new AuthorizationException('You are not authorized to access this page.');
    // }
    // Show the form for creating a new user
    public function create()
    {
        return view('users.create');
    }

    // Store a new user in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        // dd($user->hasRole('super_admin'));
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    // Update the specified user in the database
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Delete the specified user from the database
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // Assign a role to a user
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // $user->assignRole($request->input('role'));
        $user->syncRoles([$request->input('role')]);

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    // Assign a permission to a user
    public function assignPermission(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->givePermissionTo($request->input('permission'));

        return redirect()->back()->with('success', 'Permission assigned successfully.');
    }
}