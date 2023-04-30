<?php

namespace App\Nova;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class ScienceAbstract extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ScienceAbstract::class;

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
    public static $search = [];

    public static $displayInNavigation = false;

    public static $trafficCop = false;

    public static function label()
    {
        return 'Abstracts';
    }

    public static function uriKey()
    {
        return 'abstracts';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title')
                ->rules('required'),
            Text::make('Link')
                ->nullable(),
            Text::make('Authors')
                ->rules('required'),
            Text::make('Location')
                ->rules('required'),
            Text::make('City State')
                ->rules('required'),
            Date::make('Date')
                ->rules('required'),
            Markdown::make('Details'),
        ];
    }
}
