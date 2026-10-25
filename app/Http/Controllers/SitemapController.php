<?php

namespace App\Http\Controllers;

use App\Models\Pages\HomePage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = Sitemap::create();

        if ($homePage = HomePage::first()) {
            $sitemap->add(Url::create(url('/'))->setLastModificationDate($homePage->updated_at));
        }

        foreach (PageController::PAGE_MAP as $slug => $pageClass) {
            if ($page = $pageClass::first()) {
                $sitemap->add(Url::create(route('page.show', $slug))->setLastModificationDate($page->updated_at));
            }
        }

        return $sitemap->toResponse(request());
    }
}
