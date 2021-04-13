<?php

namespace App\Nova;

use DigitalCreative\ConditionalContainer\ConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class HomePage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HomePage::class;

    public static function uriKey()
    {
        return "home";
    }

    public function contentFields(Request $request)
    {
        return [
            Text::make('Tagline'),
            Image::make('Banner')->disk('public'),
            Image::make('Profile Picture')->disk('public'),
            Textarea::make('Profile Summary'),
            Markdown::make('Bio'),
            HasMany::make('Social Links', 'socialLinks')
        ];
    }
}
