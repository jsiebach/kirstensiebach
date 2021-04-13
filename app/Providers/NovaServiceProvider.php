<?php

namespace App\Providers;

use Giuga\LaravelNovaSidebar\NovaSidebar;
use Giuga\LaravelNovaSidebar\SidebarGroup;
use Giuga\LaravelNovaSidebar\SidebarLink;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use OptimistDigital\NovaSettings\NovaSettings;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        NovaSettings::addSettingsFields([
            Image::make('Favicon'),
            Code::make('Tracking Code'),
            Code::make('Schema Markup')
        ]);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new \Tightenco\NovaGoogleAnalytics\VisitorsMetric,
            new \Tightenco\NovaGoogleAnalytics\PageViewsMetric,
            new \Tightenco\NovaGoogleAnalytics\MostVisitedPagesCard,
            new \Tightenco\NovaGoogleAnalytics\ReferrersList,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (new NovaSidebar())
                ->addGroup((new SidebarGroup())
                    ->setName('Pages')
                    ->addLink((new SidebarLink())
                        ->setName('Home')
                        ->setType('_self')
                        ->setUrl('/admin/resources/home/1')
                    )
                ),
            new NovaSettings
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
