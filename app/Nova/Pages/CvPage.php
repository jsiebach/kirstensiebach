<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Image;

class CvPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\CvPage::class;

    public static function uriKey()
    {
        return 'cv';
    }

    public function contentFields(Request $request)
    {
        return [
            File::make('CV File'),
        ];
    }
}
