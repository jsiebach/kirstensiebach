<?php

namespace App\Http\Controllers;

use App\Models\Pages\CvPage;
use App\Models\Pages\LabPage;
use App\Models\Pages\HomePage;
use App\Models\Pages\OutreachPage;
use App\Models\Pages\ResearchPage;
use App\Models\Pages\PhotographyPage;
use App\Models\Pages\PublicationsPage;

class PageController extends Controller
{
    const PAGE_MAP = [
        'lab'          => LabPage::class,
        'research'     => ResearchPage::class,
        'publications' => PublicationsPage::class,
        'cv'           => CvPage::class,
        'outreach'     => OutreachPage::class,
        'photography'  => PhotographyPage::class,
    ];

    public function home()
    {
        $page = HomePage::first() ?? $this->abort();

        return view('pages.home', compact('page'));
    }

    public function view($slug)
    {
        $pageClass = self::PAGE_MAP[$slug] ?? $this->abort();

        $page = $pageClass::first() ?? $this->abort();

        return view("pages.$slug", compact('page'));
    }

    private function abort()
    {
        abort(404, 'Please go back to our <a href="' . url('') . '">homepage</a>.');
    }
}
