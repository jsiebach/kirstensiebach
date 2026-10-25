<?php

namespace App\Http\Controllers;

use App\Models\Pages\CvPage;
use App\Models\Pages\HomePage;
use App\Models\Pages\LabPage;
use App\Models\Pages\OutreachPage;
use App\Models\Pages\PhotographyPage;
use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\Pages\ResourcesPage;

class PageController extends Controller
{
    const PAGE_MAP = [
        'lab' => LabPage::class,
        'research' => ResearchPage::class,
        'publications' => PublicationsPage::class,
        'cv' => CvPage::class,
        'outreach' => OutreachPage::class,
        'photography' => PhotographyPage::class,
        'resources' => ResourcesPage::class,
    ];

    public function home()
    {
        $page = HomePage::first() ?? $this->abort();

        return view('pages.home', [
            'page' => $page,
            'structuredData' => $this->homeStructuredData($page),
        ]);
    }

    /**
     * Schema.org Person data that search engines read from the homepage.
     *
     * @return array<string, mixed>
     */
    private function homeStructuredData(HomePage $page): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => 'Kirsten Siebach',
            'url' => url('/'),
            'email' => 'ksiebach@rice.edu',
            'image' => $page->profile_picture ? url('/storage/'.$page->profile_picture) : null,
            'jobTitle' => 'Assistant Professor',
            'worksFor' => [
                '@type' => 'CollegeOrUniversity',
                'name' => 'Rice University',
                'department' => [
                    '@type' => 'Organization',
                    'name' => 'Department of Earth, Environmental and Planetary Sciences',
                ],
            ],
            'sameAs' => [
                'https://orcid.org/0000-0002-6628-6297',
                'https://mars.nasa.gov/people/info.cfm?id=22897',
                'https://www.linkedin.com/in/kirsten-siebach-33433985',
                'https://www.flickr.com/photos/77604748@N07/albums',
                'https://www.researchgate.net/profile/Kirsten_Siebach',
                'https://scholar.google.com/citations?user=TU6sGpYAAAAJ',
            ],
            'description' => 'Kirsten Siebach is an Assistant Professor in the Rice University Department of Earth, Environmental, and Planetary Sciences. Her work focuses on understanding the history of water interacting with sediments on Mars and early Earth through analysis of sedimentary rock textures and chemistry. She is currently a member of the Science and Operations Teams for the Mars Exploration Rovers and the Mars Science Laboratory.',
        ]);
    }

    public function view($slug)
    {
        $pageClass = self::PAGE_MAP[$slug] ?? $this->abort();

        $page = $pageClass::first() ?? $this->abort();

        return view("pages.$slug", compact('page'));
    }

    private function abort()
    {
        abort(404, 'Please go back to our <a href="'.url('').'">homepage</a>.');
    }
}
