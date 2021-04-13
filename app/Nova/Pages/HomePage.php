<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class HomePage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\HomePage::class;

    public static function uriKey()
    {
        return 'home';
    }

    public function contentFields(Request $request)
    {
        return [
            Text::make('Tagline'),
            Image::make('Banner')->disk('public'),
            Image::make('Profile Picture')->disk('public'),
            Textarea::make('Profile Summary'),
            Markdown::make('Bio'),
            HasMany::make('Social Links', 'socialLinks')->sortable(),
        ];
    }
}
