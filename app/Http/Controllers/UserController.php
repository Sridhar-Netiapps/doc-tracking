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
use App\Exports\UserExport;
use App\Jobs\GenerateUserExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
use App\Models\HRMData;
use Auth;
use App\AuditLogTrait;


class UserController extends Controller
{
    use AuditLogTrait; 
    // Constructor for middleware
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

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

    // Store a new user in the database
    public function store(Request $request)
    {
        // Validating the input data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_id' => 'required|unique:users,employee_id',
            'region' => 'required|string|max:255',
            'branch_id' => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
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
            'designation_id' => 'nullable|string|max:255',
            'department_id' => 'nullable|string|max:255',
        ],[
            'employee_id.unique' => 'This Employee ID already exists.',
            'email.unique'       => 'This Email is already registered.',
        ]);

        
        $is_ins_user='0';
        $is_doc_user ='0';

        if($request->module_role == 'doc'){
            $is_doc_user ='1';
        }

        if($request->module_role == 'ins'){
            $is_ins_user ='1';
        }

        if($request->module_role == 'doc_ins'){
            $is_doc_user ='1';
            $is_ins_user ='1';
        }

        $branchId = $request->branch_id ? explode('-', $request->branch_id)[0] : null;
        $regionId = $branchId ? substr((string)$branchId, 0, 1) : null;

        // Creating the new user
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'password' => Hash::make('password'),
            'employee_id' => $request->input('employee_id'),
            'region' => $request->input('region'),
            'region_id' => $regionId,
            // 'branch_id' => $request->input('branch_id'),
            'branch_id' => $branchId,
            'email' => $request->input('email'),
            'gender' => $request->input('gender'),
            'dob' => Carbon::parse($request['dob'])->format('Y-m-d'),
            // 'dob' => $request->dob ? date('Y-m-d', strtotime(str_replace('-', '/', $request->dob))) : null,
            'status' => $request->input('status'),
            'mobile_number' => $request->input('mobile_number'),
            'doj' => Carbon::parse($request['doj'])->format('Y-m-d'),
            'dor' => Carbon::parse($request['dor'])->format('Y-m-d'),
            // 'doj' => $request->doj ? date('Y-m-d', strtotime(str_replace('-', '/', $request->doj))) : null,
            // 'dor' => $request->dor ? date('Y-m-d', strtotime(str_replace('-', '/', $request->dor))) : null,
            'employee_type' => $request->input('employee_type'),
            'current_designation' => $request->input('current_designation'),
            'grade' => $request->input('grade'),
            'confirmation_status' => $request->input('confirmation_status'),
            'date_of_confirmation' => Carbon::parse($request['date_of_confirmation'])->format('Y-m-d'),
            // 'date_of_confirmation' => $request->date_of_confirmation ? date('Y-m-d', strtotime(str_replace('-', '/', $request->date_of_confirmation))) : null,
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
            'designation_id' => 0,
            'department_id' => 0,
            'module_role' => $request->module_role,
            'ins_user' => $is_ins_user,
            'doc_user' => $is_doc_user,
            'created_by' => Auth::user()->employee_id
        ]);

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        $user->syncRoles([$request->input('role')]);

        if($is_ins_user == 1){

            $module = 'Insurance'; 
             $operation = 'create';
             $note = 'Insurance Module access granted for the user - '.$request->input('employee_id');
             $link = '';

            $this->auditlogs($module , $operation ,$note , $link);

        }

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
            'region' => 'required|string|max:255',
            'branch_id' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'status' => 'required|string|max:10',
            'mobile_number' => 'required|string|max:15',
            'doj' => 'nullable|string|max:255',
            'dor' => 'nullable|string|max:255',
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
            'department_hierarchy_1_nNoame' => 'nullable|string|max:255',
            'department_hierarchy_2_name' => 'nullable|string|max:255',
            'department_hierarchy_3_name' => 'nullable|string|max:255',
            'functional_head' => 'nullable|string|max:255',
            'functional_head_emp_id' => 'nullable|string|max:255',
            'work_flow_role' => 'nullable|string|max:255',
            'prac_designation' => 'nullable|string|max:255',
            'prac_role' => 'nullable|string|max:255',
            'pac_designation' => 'nullable|string|max:255',
            'pac_role' => 'nullable|string|max:255',
            'doj' => 'required|date',
            'dor' => 'nullable|date',

        ]);

        $is_ins_user='0';
        $is_doc_user ='0';

        if($request->module_role == 'doc'){
            $is_doc_user ='1';
        }

        if($request->module_role == 'ins'){
            $is_ins_user ='1';
        }

        if($request->module_role == 'doc_ins'){
            $is_doc_user ='1';
            $is_ins_user ='1';
        }

        $userData = User::where('id',$user->id)->first();

        // Updating the user
        $user->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'employee_id' => $request->input('employee_id'),
            'region' => $request->input('region'),
            'region_id' => $request->input('region_id'),
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
            'doj' => $request->input('doj'),
            'dor' => $request->input('dor'),
            'module_role' => $request->module_role,
            'ins_user' => $is_ins_user,
            'doc_user' => $is_doc_user,

        ]);

        if($userData->ins_user == '1' && $is_ins_user == '0'){
             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Insurance Module access removed for the user - '.$request->input('employee_id');
             $link = '';

            $this->auditlogs($module , $operation ,$note , $link);
        }

        if($userData->ins_user == '0' && $is_ins_user == '1'){
             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Insurance Module access granted for the user - '.$request->input('employee_id');
             $link = '';

            $this->auditlogs($module , $operation ,$note , $link);
        }

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        $user->syncRoles([$request->input('role')]);

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
        session(['user_filters' => $request->only(['region', 'branch_id', 'employee_id', 'email','status'])]);

        if ($request->user == '1')
            return redirect()->route('user.filter');
        else
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

    public function userFilter(Request $request)
    {
        $filters = $this->normalizeUsersFilters(session('user_filters', []));
        
        $query = $this->applyUsersFilters(User::query(), $filters);
      
        $users = $query->orderBy('created_at', 'desc')->paginate(100);
    
        return view('users.index', compact('users', 'filters'));
    }
    
    public function exportCheck(Request $request)
    {
        $filters = $request->only(['region', 'branch_id', 'employee_id', 'from_date', 'to_date','status']);

        if (empty(array_filter($filters))) {
            return response()->json(['status' => 'error']);
       }

        return response()->json(['status' => 'success']);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['region', 'branch_id', 'employee_id', 'from_date', 'to_date', 'status']);
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
        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($filters['from_date'])),
                date('Y-m-d 23:59:59', strtotime($filters['to_date']))
            ]);
        } elseif (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($filters['from_date'])));
        } elseif (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($filters['to_date'])));
        }
    
        return $query;
    }

    public function userExportCheck(Request $request)
    {
        $filters = $this->normalizeUsersFilters(
            $request->only(['region', 'branch_id', 'employee_id', 'email', 'status'])
        );

        if (empty(array_filter($filters))) {
            return response()->json(['status' => 'error']);
       }

        return response()->json(['status' => 'success']);
    }

    public function userExport(Request $request)
    {
        $filters = $this->normalizeUsersFilters(
            $request->only(['region', 'branch_id', 'employee_id', 'email', 'status'])
        );

        $expectedCount = (clone $this->applyUsersFilters(User::query(), $filters))->count();

        $jobId = (string) Str::uuid();
        $cacheKey = 'user_export_' . $jobId;
        $expiresAt = now()->addHours(6);
        $path = 'exports/users_' . $jobId . '.xlsx';

        Cache::put($cacheKey, [
            'status' => 'processing',
            'user_id' => $this->user->id,
            'path' => null,
            'error' => null,
            'queued_at' => now()->toDateTimeString(),
            'expected_count' => $expectedCount,
        ], $expiresAt);

        Storage::disk('private')->makeDirectory('exports');

        try {
            GenerateUserExport::dispatch($filters, $jobId, (int) $this->user->id, $path)
                ->onQueue('exports');
        } catch (\Throwable $e) {
            Cache::put($cacheKey, [
                'status' => 'failed',
                'user_id' => $this->user->id,
                'path' => null,
                'error' => $e->getMessage(),
                'queued_at' => now()->toDateTimeString(),
            ], $expiresAt);

            return response()->json([
                'status' => 'failed',
                'error' => 'Unable to queue export. Please try again.',
            ], 500);
        }

        Log::info('User export queued', [
            'job_id' => $jobId,
            'user_id' => (int) $this->user->id,
            'filters' => $filters,
            'expected_count' => $expectedCount,
        ]);

        return response()->json([
            'job_id' => $jobId,
            'status' => 'processing',
        ]);
    }

    public function checkExportStatus(Request $request, $jobId)
    {
        $cacheKey = 'user_export_' . $jobId;
        $payload = Cache::get($cacheKey);

        if (!$payload) {
            return response()->json(['error' => 'Export job not found or expired.'], 404);
        }

        if (!isset($payload['user_id']) || (int) $payload['user_id'] !== (int) $this->user->id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if (($payload['status'] ?? null) === 'processing') {
            $queuedAtRaw = $payload['queued_at'] ?? null;
            if (!empty($queuedAtRaw)) {
                $queuedAt = Carbon::parse($queuedAtRaw);
                if ($queuedAt->addMinutes(45)->isPast()) {
                    Cache::put($cacheKey, array_merge($payload, [
                        'status' => 'failed',
                        'error' => 'Export processing timeout. Please try again.',
                    ]), now()->addHours(6));

                    return response()->json([
                        'status' => 'failed',
                        'error' => 'Export timed out. Please re-run export.',
                    ], 500);
                }
            }
        }

        if (($payload['status'] ?? null) === 'completed') {
            $path = $payload['path'] ?? null;
            if (!$path || !Storage::disk('private')->exists($path)) {
                Cache::put($cacheKey, array_merge($payload, [
                    'status' => 'failed',
                    'error' => 'Export file was not found after completion.',
                ]), now()->addHours(6));

                return response()->json([
                    'status' => 'failed',
                    'error' => 'Export file not found. Please re-run export.',
                ], 500);
            }

            $downloadUrl = URL::temporarySignedRoute(
                'user.export.download',
                now()->addMinutes(10),
                ['jobId' => $jobId]
            );

            return response()->json([
                'status' => 'completed',
                'download_url' => $downloadUrl,
            ]);
        }

        if (($payload['status'] ?? null) === 'failed') {
            return response()->json([
                'status' => 'failed',
                'error' => $payload['error'] ?? 'Export failed.',
            ], 500);
        }

        return response()->json(['status' => 'processing']);
    }

    public function downloadExport(Request $request, $jobId)
    {
        $cacheKey = 'user_export_' . $jobId;
        $payload = Cache::get($cacheKey);

        if (!$payload) {
            abort(404);
        }

        if (!isset($payload['user_id']) || (int) $payload['user_id'] !== (int) $this->user->id) {
            abort(403);
        }

        if (($payload['status'] ?? null) !== 'completed') {
            return response()->json(['error' => 'Report is not ready yet.'], 409);
        }

        $path = $payload['path'] ?? null;
        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->download($path, 'users_' . $jobId . '.xlsx');
    }

    private function applyUsersFilters($query, $filters)
    {
        $query->withoutRole('master');
        $filters = $this->normalizeUsersFilters($filters);

        if (!empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }
    
        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }
    
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
    
        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }

        if (!empty($filters['status'])) {
            $query->whereRaw('LOWER(users.status) = ?', [$filters['status']]);
        }
    
        return $query;
    }

    private function normalizeUsersFilters(array $filters): array
    {
        return [
            'region' => trim((string) ($filters['region'] ?? '')),
            'branch_id' => trim((string) ($filters['branch_id'] ?? '')),
            'employee_id' => trim((string) ($filters['employee_id'] ?? '')),
            'email' => trim((string) ($filters['email'] ?? '')),
            'status' => Str::lower(trim((string) ($filters['status'] ?? ''))),
        ];
    }
    

    public function getUser(Request $request)
    {
        return redirect()->route('users.sync',Crypt::encrypt($request->id));
    }
    // Show the form for creating a new user
    public function getUserInfo($id){     
        
        $id = Crypt::decrypt($id);
        if($id != null){
            $user = User::where('employee_id',$id)->count();
            if($user > 0){
                return redirect()->route('users.index')->with('error','This User has already access in Doc Tracker');
            }
            else{
                $user = HRMData::where('employee_id',$id)->count();
                if($user > 0){
                    $user = HRMData::where('employee_id',$id)->orderBy('load_date', 'desc')->first();
                    $roles = Role::all();
                    $permissions = Permission::all();
                    return view('users.create', compact('user', 'roles', 'permissions'));
                }
                else{
                    return redirect()->route('users.index')->with('error','User Info Does not Exists');
                }
            }
        }
        else{
            return redirect()->route('users.index')->with('error','Something Went Wrong');
        }
    }
}
