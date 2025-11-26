<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class Book extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Book>
     */
    public static $model = \App\Models\Book::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'blurb',
        'author.name',
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
            Image::make('Cover')
                ->path('covers'),
            Text::make('Title')
                ->sortable()
                ->rules('required', 'string', 'min:1', 'max:255')
                ->creationRules('unique:books,title')
                ->updateRules('unique:books,title,{{resourceId}}'),
            Trix::make('Blurb')
                ->alwaysShow()
                ->fullWidth(),
            BelongsTo::make('Author')
                ->sortable(),
            BelongsTo::make('Publisher')
                ->hideFromIndex()
                ->sortable(),
            Number::make('Pages', 'number_of_pages')
                ->filterable()
                ->hideFromIndex()
                ->rules('required', 'integer', 'min:1', 'max:10000'),
            Number::make('Copies', 'number_of_copies')
                ->sortable()
                ->rules('required', 'integer', 'min:0', 'max:10000')
                ->help('The total number of copies available in the library.'),
            Boolean::make('Featured', 'is_featured')
                ->help('Whether this book is featured on the homepage.')
                ->filterable(),
            File::make('PDF')
                ->path('pdfs'),
            URL::make('Purchase URL')
                ->displayUsing(fn($value) => $value ? parse_url($value, PHP_URL_HOST) : null)
                ->hideFromIndex(),
            HasMany::make('Audio Recordings', 'recordings', resource: Recording::class),
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
