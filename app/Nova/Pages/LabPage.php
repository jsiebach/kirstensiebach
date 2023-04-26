<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Http\Requests\NovaRequest;

class LabPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\LabPage::class;

    public static function uriKey()
    {
        return 'lab';
    }

    public function contentFields(NovaRequest $request)
    {
        return [
            Image::make('Banner')->disk('public'),
            Markdown::make('Intro'),
            HasMany::make('Team Members', 'teamMembers')->sortable(),
            Markdown::make('Lower Content'),
        ];
    }
}
