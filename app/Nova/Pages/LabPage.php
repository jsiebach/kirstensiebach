<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;

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
        return "lab";
    }

    public function contentFields(Request $request)
    {
        return [
            Image::make('Banner')->disk('public'),
        ];
    }
}
