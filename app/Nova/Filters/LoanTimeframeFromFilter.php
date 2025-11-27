<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class LoanTimeframeFromFilter extends DateFilter
{
    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        $value = Carbon::parse($value);

        return $query->whereHas('allLoans', fn($query) => $query->where('book_customer.created_at', '>=', $value));
    }
}
