<?php

namespace App\Nova\Metrics;

use App\Models\Loan;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Nova;

class LateBooks extends Trend
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): TrendResult
    {
        return $this
            ->countByDays($request, Loan::where(
                fn($query) =>
                $query
                    ->whereColumn('returned_at', '>', 'due_back_at')
                    ->orWhere(
                        fn($subQuery) =>
                        $subQuery
                            ->whereNull('returned_at')
                            ->where('due_back_at', '<', now())
                    )
            ), 'due_back_at')
            ->showSumValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array<int, string>
     */
    public function ranges(): array
    {
        return [
            10 => Nova::__('10 Days'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): DateTimeInterface|null
    {
        // return now()->addMinutes(5);

        return null;
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'late-books';
    }
}
