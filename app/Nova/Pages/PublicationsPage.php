<?php

namespace App\Nova\Pages;

use App\Nova\Page;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;

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

    public function contentFields(NovaRequest $request)
    {
        return [
            HasMany::make('Publications', 'publications'),
            HasMany::make('Science Abstracts', 'scienceAbstracts'),
        ];
    }
}
