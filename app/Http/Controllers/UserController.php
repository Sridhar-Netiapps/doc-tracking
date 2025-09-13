<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use App\Exports\ActivityExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\HRMData;
class UserController extends Controller
{
    // Constructor for middleware
    public function __construct()
    {
        // Add the permission middleware as needed for each method
        // Example:
        // $this->middleware('permission:view-user')->only('index','show');
        // $this->middleware('permission:create-user')->only(['create', 'store']);
        // $this->middleware('permission:edit-user')->only(['edit', 'update']);
        // $this->middleware('permission:delete-user')->only('destroy');
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    // List all users
    public function index()
    {
        $filter = function ($query) {
            if (!$this->user->hasAnyRole(['master', 'super_admin'])) {
                $query->where('status', 'active');
            }
            return $query;
        };
        $users = $filter(User::query())->paginate(100)->withQueryString();
        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create(){        
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
            'region' => 'required|string|max:255',
            'branch_id' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'status' => 'required|string|max:10',
            'mobile_number' => 'required|string|max:15',
            'doj' => 'required|date',
            'dor' => 'nullable|date',
            'employee_type' => 'nullable|string|max:255',
            'current_designation' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:255',
            'confirmation_status' => 'nullable|string|max:255',
            'date_of_confirmation' => 'nullable|string|max:255',
            'current_location_type' => 'nullable|string|max:255',
            'direct_manager_name' => 'nullable|string|max:255',
            'direct_manager_emp_id' => 'nullable|string|max:255',
            'direct_manager_email' => 'nullable|string|max:255',
            'office_location' => 'nullable|string|max:255',
            'current_department' => 'nullable|string|max:255',
            'top_department' => 'nullable|string|max:255',
            'department_hierarchy_1_name' => 'nullable|string|max:255',
            'department_hierarchy_2_name' => 'nullable|string|max:255',
            'department_hierarchy_3_name' => 'nullable|string|max:255',
            'functional_head' => 'nullable|string|max:255',
            'functional_head_emp_id' => 'nullable|string|max:255',
            'work_flow_role' => 'nullable|string|max:255',
            'prac_designation' => 'nullable|string|max:255',
            'prac_role' => 'nullable|string|max:255',
            'pac_designation' => 'nullable|string|max:255',
            'pac_role' => 'nullable|string|max:255',
            // 'designation_id' => 'nullable|string|max:255',
            // 'department_id' => 'nullable|string|max:255',
        ]);

        // Creating the new user
        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'password' => Hash::make('password'),
            'employee_id' => $request->input('employee_id'),
            'region' => $request->imput('region'),
            'branch_id' => $request->input('branch_id'),
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'dob' => $request->input('dob'),
            'status' => $request->input('status'),
            'mobile_number' => $request->input('mobile_number'),
            'doj' => $request->input('doj'),
            'dor' => $request->input('dor'),
            'employee_type' => $request->input('employee_type'),
            'current_designation' => $request->input('current_designation'),
            'grade' => $request->input('grade'),
            'confirmation_status' => $request->input('confirmation_status'),
            'date_of_confirmation' => $request->input('date_of_confirmation'),
            'current_location_type' => $request->input('current_location_type'),
            'direct_manager_name' => $request->input('direct_manager_name'),
            'direct_manager_emp_id' => $request->input('direct_manager_emp_id'),
            'direct_manager_email' => $request->input('direct_manager_email'),
            'office_location' => $request->input('office_location'),
            'current_department' => $request->input('current_department'),
            'top_department' => $request->input('top_department'),
            'department_hierarchy_1_name' => $request->input('department_hierarchy_1_name'),
            'department_hierarchy_2_name' => $request->input('department_hierarchy_2_name'),
            'department_hierarchy_3_name' => $request->input('department_hierarchy_3_name'),
            'functional_head' => $request->input('functional_head'),
            'functional_head_emp_id' => $request->input('functional_head_emp_id'),
            'work_flow_role' => $request->input('work_flow_role'),
            'prac_designation' => $request->input('prac_designation'),
            'prac_role' => $request->input('prac_role'),
            'pac_designation' => $request->input('pac_designation'),
            'pac_role' => $request->input('pac_role'),
            // 'designation_id' => $request->input('designation_id'),
            // 'department_id' => $request->input('department_id'),
        ]);

        // Redirecting back with success message
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show the form for editing a user
    public function edit($id)
    {
        // Fetching roles and permissions for the user
        $roles = Role::all();
        $permissions = Permission::all();
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            abort(404, 'Invalid ID');
        }
    
        $user = User::findOrFail($decryptedId);

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
            'branch_id' => 'required|string|max:255',
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
            'branch_id' => $request->input('branch_id'),
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

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        $user->syncRoles([$request->input('role')]);

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    public function assignPermission(Request $request, User $user)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $user->givePermissionTo($request->input('permission'));

        return redirect()->back()->with('success', 'Permission assigned successfully.');
    }

    public function userActivity()
    {
        $filter = function ($query) {
            // if (!$this->user->hasAnyRole(['master', 'super_admin'])) {
            //     $query->where('status', 'active');
            // }
            return $query->orderBy('created_at', 'desc');
        };
        $activites = $filter(ActivityLog::query())->paginate(100)->withQueryString();
        return view('users.activity', compact('activites'));
    }
    public function filter(Request $request)
    {
        // Store filters in session
        session(['activity_filters' => $request->only(['region', 'branch_id', 'employee_id', 'from_date', 'to_date'])]);
    
        return redirect()->route('activity.filterlist');
    }
    
    public function filterList(Request $request)
    {
        // Get filters from session
        $filters = session('activity_filters', []);
        $fromDate = $filters['from_date'] ?? null;
        $toDate = $filters['to_date'] ?? null;
    
        $query = ActivityLog::query();
    
        if (!empty($filters['region'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('region', $filters['region']);
            });
        }
    
        if (!empty($filters['branch_id'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('branch_id', $filters['branch_id']);
            });
        }
    
        if (!empty($filters['employee_id'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('employee_id', $filters['employee_id']);
            });
        }

        if ($fromDate && $toDate) {
            $start = Carbon::parse($fromDate)->startOfDay();
            $end   = Carbon::parse($toDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($fromDate) {
            $start = Carbon::parse($fromDate)->startOfDay();
            $query->where('created_at', '>=', $start);
        } elseif ($toDate) {
            $end = Carbon::parse($toDate)->endOfDay();
            $query->where('created_at', '<=', $end);
        }
        
        
            
    
        $activites = $query->orderBy('created_at', 'desc')->paginate(100);
    
        return view('users.activity', compact('activites', 'filters'));
    }
    
    public function exportCheck(Request $request)
    {
        $filters = $request->only(['region', 'branch_id', 'employee_id', 'from_date', 'to_date']);

        if (empty(array_filter($filters))) {
            return response()->json(['status' => 'error']);
       }

        return response()->json(['status' => 'success']);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['region', 'branch_id', 'employee_id']);
        $query = $this->applyActivityFilters(ActivityLog::query(), $filters);
        $data = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new ActivityExport($data), 'activity_logs.xlsx');
    }


    private function applyActivityFilters($query, $filters)
    {
        if (!empty($filters['region'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('region', $filters['region']);
            });
        }
    
        if (!empty($filters['branch_id'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('branch_id', $filters['branch_id']);
            });
        }
    
        if (!empty($filters['employee_id'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('employee_id', $filters['employee_id']);
            });
        }
    
        return $query;
    }

    public function getUser(Request $request)
    {
        if($request->id != null){
            $roles = Role::all();
            $permissions = Permission::all();
            $user = HRMData::where('employee_id',$request->id)->orderBy('load_date', 'desc')->first();
            return view('users.create', compact('user', 'roles', 'permissions'));
        }
    }
}