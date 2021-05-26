<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;

class PublicationsPage extends Page
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Pages\PublicationsPage::class;

    public static function uriKey()
    {
        return 'publications';
    }

    public function contentFields(Request $request)
    {
        return [
            HasMany::make('Publications', 'publications'),
        ];
    }
}
