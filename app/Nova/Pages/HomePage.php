<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class HomePage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\HomePage::class;

    public $showCalloutSection = true;

    public static function uriKey()
    {
        return 'home';
    }

    public function contentFields(NovaRequest $request)
    {
        return [
            Text::make('Tagline'),
            Image::make('Banner')->disk('public'),
            Image::make('Profile Picture')->disk('public'),
            Textarea::make('Profile Summary'),
            Markdown::make('Bio'),
            HasMany::make('Press', 'press')->sortable(),
            HasMany::make('Social Links', 'socialLinks')->sortable(),
        ];
    }
}
