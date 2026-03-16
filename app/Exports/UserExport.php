<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterExport;
use Maatwebsite\Excel\Events\ExportFailed;
use Throwable;


class UserExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithEvents, ShouldQueue
{
    protected array $filters;
    protected string $jobId;
    protected string $path;

    public function __construct(array $filters = [], string $jobId = '', string $path = '')
    {
        $this->filters = $filters;
        $this->jobId = $jobId;
        $this->path = $path;
    }

    public function query()
    {
        $query = User::query();
        $query->withoutRole('master');

        if (!empty($this->filters['region'])) {
            $query->where('region', $this->filters['region']);
        }
    
        if (!empty($this->filters['branch_id'])) {
            $query->where('branch_id', $this->filters['branch_id']);
        }
    
        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }
    
        if (!empty($this->filters['email'])) {
            $query->where('email', $this->filters['email']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query
            ->orderBy('created_at', 'desc')
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
            ])
            ->with([
                'roles',
                'creator:id,first_name',
                'modifier:id,first_name',
                'lastLogin' => function ($q) {
                    $q->latest('created_at');
                },
            ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            AfterExport::class => function () {
                $this->updateCache([
                    'status' => 'completed',
                    'path' => $this->path,
                    'error' => null,
                ]);
            },
            ExportFailed::class => function (ExportFailed $event) {
                $message = null;
                $exception = null;

                if (property_exists($event, 'exception') && $event->exception instanceof Throwable) {
                    $exception = $event->exception;
                } elseif (property_exists($event, 'e') && $event->e instanceof Throwable) {
                    $exception = $event->e;
                }

                if ($exception) {
                    $message = $exception->getMessage();
                }

                $this->updateCache([
                    'status' => 'failed',
                    'error' => $message ?: 'Export failed.',
                ]);
            },
        ];
    }

    private function updateCache(array $updates): void
    {
        if ($this->jobId === '') {
            return;
        }

        $cacheKey = 'user_export_' . $this->jobId;
        $payload = Cache::get($cacheKey);
        if (!is_array($payload)) {
            $payload = [];
        }

        foreach ($updates as $key => $value) {
            $payload[$key] = $value;
        }

        Cache::put($cacheKey, $payload, now()->addHours(2));
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
        $count = 0;
        $count++;
        // dd($row);
        $roles = $row->roles->pluck('name')->map(function ($role) {
            return $role === 'super_admin' ? 'ID Maintenance' : $role;
        })->implode(', ');

        return [
            $count,
            trim(($row->first_name ?? '') . ' ' . ($row->middle_name ?? '') . ' ' . ($row->last_name ?? '')),
            $row->employee_id ?? '-',
            $row->region ?? '-',
            $row->branch_id ?? '-',
            $roles ?: '-',
            $row->status ?? '-',
            $row->lastLogin != null ? Carbon::parse($row->lastLogin->created_at)->format('d-M-Y') : '-', 
            $row->creator != null ? $row->creator->first_name: '-',
            $row->created_at ? Carbon::parse($row->created_at)->format('d-M-Y') : '-', 
            $row->modifier != null ? $row->modifier->first_name: '-',
            $row->updated_at ? Carbon::parse($row->updated_at)->format('d-M-Y') : '-', 

        ];
    }
}
