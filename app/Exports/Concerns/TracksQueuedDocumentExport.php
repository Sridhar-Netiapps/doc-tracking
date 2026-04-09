<?php

namespace App\Exports\Concerns;

use Illuminate\Support\Facades\Cache;
use Throwable;

trait TracksQueuedDocumentExport
{
    protected ?string $jobId = null;
    protected ?int $requestedBy = null;

    protected function bootQueueTracking(?string $jobId, ?int $requestedBy): void
    {
        $this->jobId = $jobId;
        $this->requestedBy = $requestedBy;
    }

    public function failed(Throwable $exception): void
    {
        if (empty($this->jobId) || empty($this->requestedBy)) {
            return;
        }

        $cacheKey = 'document_export_' . $this->jobId;
        $payload = Cache::get($cacheKey, []);

        Cache::put($cacheKey, array_merge($payload, [
            'status' => 'failed',
            'user_id' => $this->requestedBy,
            'path' => null,
            'error' => $exception->getMessage(),
            'failed_at' => now()->toDateTimeString(),
        ]), now()->addHours(24));
    }
}

