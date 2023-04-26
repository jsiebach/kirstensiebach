<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            //            new \Tightenco\NovaGoogleAnalytics\VisitorsMetric,
            //            new \Tightenco\NovaGoogleAnalytics\PageViewsMetric,
            //            new \Tightenco\NovaGoogleAnalytics\MostVisitedPagesCard,
            //            new \Tightenco\NovaGoogleAnalytics\ReferrersList,
        ];
    }
}
