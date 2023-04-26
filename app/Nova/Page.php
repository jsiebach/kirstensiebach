<?php

namespace App\Nova;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Page extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public static $searchable = false;

    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Title')->sortable()->required(),
            new Panel('SEO Settings', $this->seoSettingsFields()),
            new Panel('Content', $this->contentFields($request)),
        ];
    }

    public function fieldsForIndex()
    {
        return [
            Text::make('Title')->sortable(),
        ];
    }

    private function seoSettingsFields()
    {
        return [
            Text::make('Meta Title')->required(),
            Textarea::make('Meta Description')->nullable(),
        ];
    }
}
