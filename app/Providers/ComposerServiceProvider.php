<?php

namespace App\Providers;

use App\Models\Publication;
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
        View::composer('pages.publications', function ($view) {
            dd($view);
            $submitted = collect(['Submitted and In Review' => Publication::wherePublished('false')->get()]);
            $published = Publication::wherePublished(true)->orderBy('date', 'desc')->get()->groupBy(function($pub){
                return $pub->date_published->format('Y');
            });
            foreach($published as $key => $values){
                $submitted->put($key, $values);
            }

            $abstracts = []; //ScienceAbstract::orderBy('created_at', 'DESC')->get();
            $view->with(['publications' => $submitted, 'abstracts' => $abstracts]);
        });
    }
}
