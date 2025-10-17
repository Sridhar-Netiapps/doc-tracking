<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class UserExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
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
            'creation Date',
            'Last Modify Date'

        ];
    }

    public function map($row): array
    {
        static $count = 0;
        $count++;
        // dd($row);
        $roles = $row->roles->pluck('name')->map(function ($role) {
            return $role === 'super_admin' ? 'id_maintenance' : $role;
        })->implode(', ');

        return [
            $count,
            trim(($row->first_name ?? '') . ' ' . ($row->middle_name ?? '') . ' ' . ($row->last_name ?? '')),
            $row->employee_id ?? '-',
            $row->region ?? '-',
            $row->branch_id ?? '-',
            $roles ?: '-',
            $row->status ?? '-',
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y') : '-', 
            $row->updated_at ? Carbon::parse($row->created_at)->format('d-m-Y') : '-', 

        ];
    }
}
