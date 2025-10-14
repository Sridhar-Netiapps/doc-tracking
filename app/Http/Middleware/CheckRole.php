<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|array  $roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next, ...$roles)
    // {
    //     if (!auth()->check() || !auth()->user()->hasAnyRole($roles)) {
    //         return redirect()->route('home')->with('error', 'Access denied.');
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        
        // Define restricted routes for roles
        $restrictedRoutes = [
            'bo-checker' => [
                'documents/moved',
                'document/reports',
                'users',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'users/activities',
                'roles',
                'permissions'
            ],
            'bo-maker' => [
                'documents/moved',
                'document/reports',
                'users',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'roles',
                'permissions'
            ],
            'ro-officer' => [
                'users/activities',
                'users/create',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'roles',
                'permissions'
            ],
            'ro-supervisor' => [
                'users/activities',
                'users/create',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'roles',
                'permissions'
            ],
            'branch-user' => [
                'documents/moved',
                'document/reports',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'users/activities',
                'roles',
                'users/create',
                'permissions'
            ],
            'ho-user' => [
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'users/activities',
                'roles',
                'users/create',
                'permissions'
            ],
            'ro-user' => [
                'document/reports',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'users/activities',
                'roles',
                'users/create',
                'permissions'
            ],
            'admin' => [
                'roles',
                'users/create',
                'permissions'
            ],
            'super_admin' => [
                'home',
                'documents',
                'dispatches',
                'document/reports',
                'vendor',
                'emails',
                'process-status',
                'couriers',
                'document/trashed',
                'roles',
                'permissions' 
            ],
            'master' => [],
            // Add more roles if needed
        ];

      //print_r(Auth::user()->doc_user);die();//

        foreach ($restrictedRoutes as $role => $routes) {
            if ($user->hasRole($role)) {
                foreach ($routes as $route) {
                    if ($request->is($route) || $request->is($route.'/*')) {
                        // Role-based redirect
                        if ($user->hasRole('super_admin')) {
                            return redirect('/users')->with('error', 'Access Denied');
                        }
        
                        return redirect('/home')->with('error', 'Access Denied');
                    }
                }
            }
        }
        
        if (Auth::user()->doc_user == '0' && !$request->is('home')) {
            abort(403, 'DocTrack access is required.');
        }


        return $next($request);
    }
    
}