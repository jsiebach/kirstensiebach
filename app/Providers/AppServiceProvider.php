<?php

namespace App\Providers;

use Illuminate\Mail\Markdown;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
