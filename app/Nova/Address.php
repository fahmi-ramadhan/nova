<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Address extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Address>
     */
    public static $model = \App\Models\Address::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'line_1';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'line_1',
        'city',
        'county',
        'postcode',
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

            MorphTo::make('Addressable')
                ->types([
                    Customer::class,
                    Publisher::class,
                ]),

            Text::make('Line 1')
                ->rules('required', 'string', 'max:255'),

            Text::make('Line 2')
                ->rules('nullable', 'string', 'max:255'),

            Select::make('City')
                ->rules('required', 'string', 'max:255')
                ->searchable()
                ->options(
                    fn() => collect(json_decode(file_get_contents(resource_path('data/cities.json')), true))
                        ->mapWithKeys(fn($county) => [$county['city'] => $county['city']])
                        ->all()
                ),

            Select::make('County')
                ->rules('required', 'string', 'max:255')
                ->searchable()
                ->options(
                    fn() => collect(json_decode(file_get_contents(resource_path('data/counties.json')), true))
                        ->mapWithKeys(fn($county) => [$county['name'] => $county['name']])
                        ->all()
                ),

            Text::make('Postcode')
                ->rules('required', 'string', 'max:255', 'regex:/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([AZa-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) [0-9][A-Za-z]{2})$/'),
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
        return [];
    }
}
