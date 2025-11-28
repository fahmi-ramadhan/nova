<?php

namespace App\Nova\Metrics;

use App\Models\Book;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Progress;
use Laravel\Nova\Metrics\ProgressResult;

class NewBooks extends Progress
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): ProgressResult
    {
        return $this->count(
            $request,
            Book::class,
            progress: fn($query) => $query->where('created_at', '>=', now()->startOfMonth()),
            target: 100,
        );
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
        return 'new-books';
    }
}
