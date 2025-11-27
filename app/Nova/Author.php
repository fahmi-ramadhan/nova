<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Author extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Author>
     */
    public static $model = \App\Models\Author::class;

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
            Avatar::make('Avatar')
                ->showWhenPeeking()
                ->rounded()
                ->rules('required', File::image()->max(1024 * 10))
                ->path('authors'),
            Text::make('Name')
                ->showWhenPeeking()
                ->sortable()
                ->rules('required', 'string', 'max:255'),
            Trix::make('Biography')
                ->showWhenPeeking()
                ->fullWidth()
                ->rules('required', 'string', 'max:10000'),

        ];
    }

    public function subtitle()
    {
        return str($this->biography)->limit(120)->stripTags();
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
