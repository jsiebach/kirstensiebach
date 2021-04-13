<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;

class ResearchPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\ResearchPage::class;

    public static function uriKey()
    {
        return 'research';
    }

    public function contentFields(Request $request)
    {
        return [
            Image::make('Banner')->disk('public'),
        ];
    }
}
