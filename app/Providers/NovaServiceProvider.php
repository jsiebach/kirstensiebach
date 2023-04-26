<?php

namespace App\Providers;

use App\Models\Page;
use App\Nova\Dashboards\Main;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Outl1ne\NovaSettings\NovaSettings;
use vmitchell85\NovaLinks\Links;

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

        $sidebarGroup = (new Links('Pages'));

        $pages->each(fn ($page) => $sidebarGroup->addLink($page->title, "/resources/{$page->slug}/{$page->id}"));

        return [
            $sidebarGroup,
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
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main,
        ];
    }
}
