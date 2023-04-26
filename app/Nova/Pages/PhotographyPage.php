<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Http\Requests\NovaRequest;

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

    public function contentFields(NovaRequest $request)
    {
        return [];
    }
}
