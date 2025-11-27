<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class LoanTimeframeFilter extends DateFilter
{
    public function __construct(private string $operator) {}

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        $value = Carbon::parse($value);

        return $query->whereHas('allLoans', fn($query) => $query->where('book_customer.created_at', $this->operator, $value));
    }

    public function name()
    {
        return match ($this->operator) {
            '<=' => 'Loans before',
            '>=' => 'Loans after',
            default => 'Loans on',
        };
    }

    public function key()
    {
        return "loan-filter-{$this->operator}";
    }
}
