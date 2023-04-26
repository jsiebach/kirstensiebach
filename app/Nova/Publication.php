<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Publication extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Publication::class;

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
        return 'Publications';
    }

    public static function uriKey()
    {
        return 'journal-publications';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title'),
            Markdown::make('Authors'),
            Text::make('Publication Name'),
            Boolean::make('Published')->help('Has this been published, or is it still in review?'),
            Date::make('Date Published')->help('Date submitted if not yet published'),
            Textarea::make('Abstract')->nullable(),
            Text::make('DOI')->nullable(),
            Text::make('Link')->nullable(),
        ];
    }
}
