<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;

class PhotographyPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\PhotographyPage::class;

    public static function uriKey()
    {
        return 'photography';
    }

    public function contentFields(Request $request)
    {
        return [];
    }
}
