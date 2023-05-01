<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
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

    public $showCalloutSection = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $fields = [];

        $fields[] = Text::make('Title')->sortable()->required();

        $fields[] = new Panel('SEO Settings', $this->seoSettingsFields($request));

        if ($this->showCalloutSection) {
            $fields[] = new Panel('Call to Action', $this->calloutFields($request));
        }

        $fields[] = new Panel('Content', $this->contentFields($request));

        return $fields;
    }

    public function fieldsForIndex()
    {
        return [
            Text::make('Title')->sortable(),
        ];
    }

    private function seoSettingsFields(NovaRequest $request)
    {
        return [
            Text::make('Meta Title')->required(),
            Textarea::make('Meta Description')->nullable(),
        ];
    }

    public function contentFields(NovaRequest $request)
    {
        return [];
    }

    public function calloutFields(NovaRequest $request)
    {
        return [
            Boolean::make('Add Call to Action Banner'),
            Textarea::make('Call to Action'),
            Text::make('Action Link'),
            Text::make('Action Text'),
        ];
    }
}
