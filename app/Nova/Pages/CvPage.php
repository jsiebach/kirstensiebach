<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;

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

    public function contentFields(NovaRequest $request)
    {
        return [
            File::make('CV File'),
        ];
    }
}
