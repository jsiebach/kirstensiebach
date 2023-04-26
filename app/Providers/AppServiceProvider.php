<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('markdown', function ($expression) {
            return $expression
                ? '<?php echo \Illuminate\Mail\Markdown::parse("'.$expression.'"); ?>'
                : '';
        });
    }
}
