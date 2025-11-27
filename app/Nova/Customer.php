<?php

namespace App\Nova;

use App\Nova\Filters\HasReviewsFilter;
use App\Nova\Filters\LoanTimeframeFromFilter;
use App\Nova\Filters\LoanTimeframeToFilter;
use App\Nova\Relationships\LoanFields;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Customer>
     */
    public static $model = \App\Models\Customer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()
                ->sortable(),
            Text::make('Name')
                ->rules('required', 'string', 'max:255'),
            Email::make('Email')
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:customers,email')
                ->updateRules('unique:customers,email,{{resourceId}}'),
            DateTime::make('Joined At')
                ->filterable()
                ->rules('required', 'date', 'before_or_equal:today')
                ->max(now())
                ->step(CarbonInterval::minutes(1)),
            MorphOne::make('Address')
                ->required(),
            BelongsToMany::make('Current Loans', resource: Book::class)
                ->fields(new LoanFields()),
            MorphMany::make('Reviews'),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new HasReviewsFilter(),
            new LoanTimeframeFromFilter(),
            new LoanTimeframeToFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
