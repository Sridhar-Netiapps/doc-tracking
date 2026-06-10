<?php

namespace App\Jobs;

use App\Exports\UserExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class GenerateUserExport implements ShouldQueue
{
    use Queueable;

    public $timeout = 1800;    // 30 min — enough for ~10L xlsx
    public $tries = 2;
    public $backoff = 60;

    public function __construct(
        private readonly array $filters,
        private readonly string $jobId,
        private readonly int $userId,
        private readonly string $path
    ) {
    }

    public function handle(): void
    {
        $cacheKey = 'user_export_' . $this->jobId;
        $export = new UserExport($this->filters);
        $matchingCount = (clone $export->query())->count();

        try {
            Storage::disk('private')->makeDirectory('exports');
            Excel::store($export, $this->path, 'private');

            Cache::put($cacheKey, [
                'status' => 'completed',
                'user_id' => $this->userId,
                'path' => $this->path,
                'error' => null,
                'completed_at' => now()->toDateTimeString(),
                'matching_count' => $matchingCount,
            ], now()->addHours(6));

            Log::info('User export completed', [
                'job_id' => $this->jobId,
                'user_id' => $this->userId,
                'path' => $this->path,
                'matching_count' => $matchingCount,
            ]);
        } catch (Throwable $e) {
            $this->markFailed($e->getMessage());
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->markFailed($exception->getMessage());
    }

    private function markFailed(?string $message): void
    {
        $cacheKey = 'user_export_' . $this->jobId;

        Cache::put($cacheKey, [
            'status' => 'failed',
            'user_id' => $this->userId,
            'path' => null,
            'error' => $message ?: 'Export failed.',
        ], now()->addHours(6));
    }
}
