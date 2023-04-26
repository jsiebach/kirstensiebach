<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Http\Requests\NovaRequest;

class OutreachPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\OutreachPage::class;

    public static function uriKey()
    {
        return 'outreach';
    }

    public function contentFields(NovaRequest $request)
    {
        return [];
    }
}
