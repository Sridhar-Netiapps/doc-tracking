<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;


class ActivityExport implements FromCollection, WithHeadings, WithMapping
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
            'Email',
            'Event Type',
            'Description',
            'Activity on',
        ];
    }

    public function map($row): array
    {
        static $count = 0;
        $count++;

        return [
            $count,
            trim($row->user->first_name . ' ' . $row->user->middle_name . ' ' . $row->user->last_name),
            $row->user->employee_id,
            $row->user->region,
            $row->user->branch_id,
            $row->user->email,
            ucfirst($row->event_type),
            $row->description,
            date('d-m-Y h:i A', strtotime($row->created_at)),
        ];
    }
}
