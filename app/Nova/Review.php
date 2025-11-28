<?php

namespace App\Nova;

use App\Models\Review as ModelsReview;
use App\Nova\Actions\DestroyUnverifiedReviews;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Review extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Review>
     */
    public static $model = \App\Models\Review::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public static $with = ['reviewable'];

    public static $globalSearchResults = 10;

    public function subtitle()
    {
        return match ($this->reviewable::class) {
            \App\Models\Author::class => $this->reviewable->name,
            \App\Models\Book::class => $this->reviewable->title,
            default => null,
        };
    }

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
            MorphTo::make('Reviewer')
                ->types([
                    User::class,
                    Customer::class,
                ]),
            MorphTo::make('Reviewable')
                ->types([
                    Author::class,
                    Book::class,
                ]),
            Text::make('Title')
                ->rules('required', 'string', 'max:255'),
            Text::make('Body')
                ->displayUsing(function ($value, $resource, $attribute) use ($request) {
                    if ($request->isResourceIndexRequest()) {
                        return str()->limit($value, 75);
                    }
                    return $value;
                })
                ->rules('required', 'string', 'max:65535'),
            Number::make('Stars')
                ->rules('required', 'integer', 'min:1', 'max:5'),
            DateTime::make('Verified At')
                ->nullable()
                ->hideFromIndex()
                ->rules('nullable', 'date'),
            Boolean::make('Verified', fn() => $this->verified_at !== null)
                ->filterable()
                ->exceptOnForms(),
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
        return [];
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
        return [
            Action::using(
                'Verify',
                fn(ActionFields $fields, Collection $models) =>
                ModelsReview::whereKey($models->pluck('id'))
                    ->whereNull('verified_at')
                    ->update(['verified_at' => now()])
            ),
            (new DestroyUnverifiedReviews())
                ->standalone()
                ->confirmText('Are you sure you want to delete all unverified reviews? This action cannot be undone.'),
        ];
    }
}
