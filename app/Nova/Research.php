<?php

namespace App\Nova;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Research extends Resource
{
    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Research::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'project_name';

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
        return 'Research Projects';
    }

    public static function uriKey()
    {
        return 'research-project';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Project Name'),
            Markdown::make('Description'),
            Image::make('Image'),
        ];
    }
}
