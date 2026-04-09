<?php

namespace App\Exports;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class UserExport implements FromQuery, WithHeadings, WithMapping
{
    protected array $filters;
    protected int $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::query();
        $query->withoutRole('master');

        $region = trim((string) ($this->filters['region'] ?? ''));
        if ($region !== '') {
            $query->where('region', $region);
        }
    
        $branchId = trim((string) ($this->filters['branch_id'] ?? ''));
        if ($branchId !== '') {
            $query->where('branch_id', $branchId);
        }
    
        $employeeId = trim((string) ($this->filters['employee_id'] ?? ''));
        if ($employeeId !== '') {
            $query->where('employee_id', $employeeId);
        }
    
        $email = trim((string) ($this->filters['email'] ?? ''));
        if ($email !== '') {
            $query->where('email', $email);
        }

        $status = Str::lower(trim((string) ($this->filters['status'] ?? '')));
        if ($status !== '') {
            $query->whereRaw('LOWER(users.status) = ?', [$status]);
        }

        $query->orderBy('users.id')
            ->select([
                'id',
                'first_name',
                'middle_name',
                'last_name',
                'employee_id',
                'region',
                'branch_id',
                'status',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'last_login_at' => ActivityLog::query()
                    ->select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->where('route', 'login')
                    ->latest('created_at')
                    ->limit(1),
            ])
            ->with([
                'roles:id,name',
                'creator:id,first_name',
                'modifier:id,first_name',
            ]);

        return $query;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Name',
            'Employee ID',
            'Region',
            'Branch Code',
            'Role',
            'Status',
            'Last Login Date',
            'Created By',
            'Creation Date',
            'Last Modify By',
            'Last Modify Date'
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        $roles = $row->roles->pluck('name')->map(function ($role) {
            return $role === 'super_admin' ? 'ID Maintenance' : $role;
        })->implode(', ');

        return [
            $this->rowNumber,
            trim(($row->first_name ?? '') . ' ' . ($row->middle_name ?? '') . ' ' . ($row->last_name ?? '')),
            $row->employee_id ?? '-',
            $row->region ?? '-',
            $row->branch_id ?? '-',
            $roles ?: '-',
            $row->status ?? '-',
            $row->last_login_at ? Carbon::parse($row->last_login_at)->format('d-M-Y') : '-', 
            $row->creator != null ? $row->creator->first_name: '-',
            $row->created_at ? Carbon::parse($row->created_at)->format('d-M-Y') : '-', 
            $row->modifier != null ? $row->modifier->first_name: '-',
            $row->updated_at ? Carbon::parse($row->updated_at)->format('d-M-Y') : '-', 

        ];
    }
}
