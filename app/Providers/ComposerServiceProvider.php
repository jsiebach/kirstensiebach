<?php

namespace App\Providers;

use App\Models\Pages\ResourcesPage;
use App\Models\Publication;
use App\Models\ScienceAbstract;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('partials.header', function ($view) {
            $view->with('showResourcesInMenu', ResourcesPage::isListedInMenu());
        });

        View::composer('pages.resources', function ($view) {
            $page = $view->getData()['page'];
            $view->with([
                'unsectionedResourceItems' => $page->unsectionedResourceItems(),
                'resourceSections' => $page->resourceSections(),
            ]);
        });

        View::composer('pages.publications', function ($view) {
            $submitted = collect(['Submitted and In Review' => Publication::wherePublished('false')->get()]);
            $published = Publication::wherePublished(true)->get()->groupBy(function ($pub) {
                return $pub->date_published->format('Y');
            });
            foreach ($published as $key => $values) {
                $submitted->put($key, $values);
            }

            $abstracts = ScienceAbstract::all();
            $view->with([
                'publications' => $submitted, 'abstracts' => $abstracts,
            ]);
        });
    }
}
