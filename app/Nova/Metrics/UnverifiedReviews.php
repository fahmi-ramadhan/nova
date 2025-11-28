<?php

namespace App\Nova\Metrics;

use App\Models\Review;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Metrics\MetricTableRow;
use Laravel\Nova\Metrics\Table;

class UnverifiedReviews extends Table
{
    /**
     * Calculate the value of the metric.
     *
     * @return array<int, \Laravel\Nova\Metrics\MetricTableRow>
     */
    public function calculate(NovaRequest $request): array
    {
        return Review::whereNull('verified_at')
            ->with('reviewer')
            ->latest()
            ->limit(5)
            ->get()
            ->map(
                fn($review) => MetricTableRow::make()
                    ->title($review->title)
                    ->subtitle('By ' . $review->reviewer->name)
                    ->actions(fn() => [
                        MenuItem::link('View', 'resources/reviews/' . $review->id),
                    ])

            )->toArray();
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): DateTimeInterface|null
    {
        return now()->addMinutes(5);
    }
}
