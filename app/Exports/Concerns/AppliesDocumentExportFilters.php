<?php

namespace App\Exports\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait AppliesDocumentExportFilters
{
    protected function applyDocumentExportFilters(Builder $query, string $table, array $filters): Builder
    {
        $fromDate = !empty($filters['from_date']) ? Carbon::parse($filters['from_date'])->startOfDay() : null;
        $toDate = !empty($filters['to_date']) ? Carbon::parse($filters['to_date'])->endOfDay() : Carbon::now()->endOfDay();

        foreach ($filters as $field => $value) {
            if (empty($value) || !Schema::hasColumn($table, $field)) {
                continue;
            }

            if (in_array($field, ['cif_id', 'account_number'], true)) {
                $query->where($field, 'like', '%' . $value . '%');
            } elseif (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        if (!empty($filters['courier_name'])) {
            $courierId = $filters['courier_name'];
            $query->whereHas('dispatch.courierName', function ($q) use ($courierId) {
                $q->where('id', $courierId);
            });
        }

        if (!empty($filters['from_date']) && !empty($filters['to_date']) && !empty($filters['date_field'])) {
            $dateField = $filters['date_field'];

            $dispatchField = match ($dateField) {
                'dispatch_date' => 'dispatch_date',
                default => null,
            };

            $mainField = match ($dateField) {
                'creation_date' => 'account_creation_date',
                'movement_date' => 'vendor_movement_date',
                'activity_date' => 'updated_at',
                default => null,
            };

            if ($dateField === 'received_date') {
                $query->whereHas('getReceivedDetails', function ($q) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween('created_at', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $q->whereDate('created_at', '>=', $fromDate);
                    } elseif ($toDate) {
                        $q->whereDate('created_at', '<=', $toDate);
                    }
                    $q->whereIn('current_status', [5, 7]);
                });
            }

            if ($dispatchField) {
                $query->whereHas('dispatch', function ($q) use ($dispatchField, $fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween($dispatchField, [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $q->whereDate($dispatchField, '>=', $fromDate);
                    } elseif ($toDate) {
                        $q->whereDate($dispatchField, '<=', $toDate);
                    }
                });
            } elseif ($mainField) {
                $query->where(function ($q) use ($mainField, $fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween($mainField, [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $q->whereDate($mainField, '>=', $fromDate);
                    } elseif ($toDate) {
                        $q->whereDate($mainField, '<=', $toDate);
                    }
                });
            }
        }

        return $query->orderBy('account_creation_date', 'desc');
    }
}
