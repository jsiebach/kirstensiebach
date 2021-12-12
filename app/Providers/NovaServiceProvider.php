<?php

namespace App\Providers;

use App\Models\Page;
use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Image;
use Illuminate\Support\Facades\Gate;
use Giuga\LaravelNovaSidebar\NovaSidebar;
use Giuga\LaravelNovaSidebar\SidebarLink;
use Giuga\LaravelNovaSidebar\SidebarGroup;
use OptimistDigital\NovaSettings\NovaSettings;
use Laravel\Nova\NovaApplicationServiceProvider;

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
            Code::make('Schema Markup'),
        ]);
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        $pages = Page::all();

        $sidebarGroup = (new SidebarGroup())
            ->setName('Pages');

        $pages->each(fn ($page) => $sidebarGroup->addLink((new SidebarLink())
            ->setName($page->title)
            ->setType('_self')
            ->setUrl("/admin/resources/{$page->slug}/{$page->id}")));

        return [
            (new NovaSidebar())
                ->addGroup($sidebarGroup),
            new NovaSettings,
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
                'jsiebach@gmail.com',
                'ksiebach@gmail.com',
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
}
