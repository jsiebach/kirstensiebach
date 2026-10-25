<?php

declare(strict_types=1);

use App\Models\Pages\HomePage;
use App\Models\Pages\ResearchPage;

test('sitemap lists the home page and existing content pages', function (): void {
    HomePage::create(['title' => 'Home', 'slug' => 'home', 'meta_title' => 'Home']);
    ResearchPage::factory()->create(['slug' => 'research']);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/xml; charset=utf-8')
        ->assertSee('<loc>'.url('/').'</loc>', false)
        ->assertSee('<loc>'.url('/research').'</loc>', false)
        ->assertDontSee('<loc>'.url('/lab').'</loc>', false);
});
