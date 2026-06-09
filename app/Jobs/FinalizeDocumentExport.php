<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FinalizeDocumentExport implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;
    public int $tries = 1;

    public function __construct(
        private readonly string $jobId,
        private readonly int $userId,
        private readonly string $path,
        private readonly int $expectedCount
    ) {
    }

    public function handle(): void
    {
        $cacheKey = 'document_export_' . $this->jobId;
        $payload = Cache::get($cacheKey, []);
        Log::info('Document export job ', [
            'job_id' => $this->jobId,
            'user_id' => $this->userId,
            'path' => $this->path,
            'expected_count' => $this->expectedCount,
        ]);
        if (!Storage::disk('private')->exists($this->path)) {
            Cache::put($cacheKey, array_merge($payload, [
                'status' => 'failed',
                'user_id' => $this->userId,
                'path' => null,
                'error' => 'Export file not found after queue completion.',
                'failed_at' => now()->toDateTimeString(),
            ]), now()->addHours(24));

            return;
        }

        Log::info('Document export write ', [
            'job_id' => $this->jobId,
            'user_id' => $this->userId,
            'path' => $this->path,
            'expected_count' => $this->expectedCount,
        ]);

        Cache::put($cacheKey, array_merge($payload, [
            'status' => 'completed',
            'user_id' => $this->userId,
            'path' => $this->path,
            'error' => null,
            'completed_at' => now()->toDateTimeString(),
            'expected_count' => $this->expectedCount,
        ]), now()->addHours(24));

        Log::info('Document export completed', [
            'job_id' => $this->jobId,
            'user_id' => $this->userId,
            'path' => $this->path,
            'expected_count' => $this->expectedCount,
        ]);
    }
}

