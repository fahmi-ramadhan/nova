<?php

namespace App\Nova\Lenses;

use App\Nova\Filters\StockFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;

class BookStock extends Lens
{
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    /**
     * Get the query builder / paginator for the lens.
     */
    public static function query(LensRequest $request, Builder $query): Builder|Paginator
    {
        return $request->withOrdering($request->withFilters(
            $query->fromSub(
                fn($query) => $query
                    ->from('books')
                    ->select('id', 'cover', 'title', 'number_of_copies')
                    ->addSelect([
                        'copies_on_loan' => fn($query) => $query->selectRaw('count(*)')
                            ->from('book_customer')
                            ->whereColumn('book_customer.book_id', 'books.id')
                            ->whereNull('book_customer.returned_at'),
                        'copies_in_stock' => fn($query) => $query->selectRaw('number_of_copies - count(*)')
                            ->from('book_customer')
                            ->whereColumn('book_customer.book_id', 'books.id')
                            ->whereNull('book_customer.returned_at'),
                    ]),
                'books',
            )
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(Nova::__('ID'), 'id')
                ->sortable(),
            Image::make('Cover'),
            Text::make('Title')
                ->sortable(),
            Number::make('Number of Copies')
                ->sortable(),
            Number::make('Copies on Loan')
                ->sortable(),
            Number::make('Copies in Stock')
                ->sortable(),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new StockFilter(),
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     */
    public function uriKey(): string
    {
        return 'book-stock';
    }
}
