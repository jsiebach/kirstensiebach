<?php

declare(strict_types=1);

use App\Models\Pages\OutreachPage;

test('outreach page shows the videos, photos and lists entered in the admin', function (): void {
    OutreachPage::factory()->create(['content' => [
        'intro' => 'Exploring **Mars** with students.',
        'contact_callout' => 'Email [outreach@kirstensiebach.com](mailto:outreach@kirstensiebach.com)',
        'videos' => [
            ['title' => 'Gale Crater Story', 'youtube_url' => 'https://youtu.be/iMt7azVQsCs', 'description' => 'A public lecture.'],
            ['title' => 'Houston Geological Society', 'youtube_url' => 'https://www.youtube.com/watch?v=NfpzGW2t_KU', 'description' => null],
        ],
        'photos' => ['outreach/school-visit.jpg'],
        'sections' => [
            ['heading' => 'Recent Keynote Talks', 'entries' => [
                ['date' => '4/11/2019', 'description' => 'West Texas Geological Foundation', 'link' => 'https://example.com/wtgf'],
            ]],
        ],
    ]]);

    $this->get('/outreach')
        ->assertOk()
        ->assertSee('<strong>Mars</strong>', false)
        ->assertSee('href="mailto:outreach@kirstensiebach.com"', false)
        ->assertSeeInOrder(['Gale Crater Story', 'data-youtube-id="iMt7azVQsCs"', 'A public lecture.', 'Houston Geological Society', 'data-youtube-id="NfpzGW2t_KU"'], false)
        ->assertSee('src="/storage/outreach/school-visit.jpg"', false)
        ->assertSeeInOrder(['Recent Keynote Talks', '4/11/2019', 'West Texas Geological Foundation', 'https://example.com/wtgf']);
});

test('outreach page leaves out empty sections and videos without a youtube link', function (): void {
    OutreachPage::factory()->create(['content' => [
        'videos' => [['title' => 'Broken Video', 'youtube_url' => 'https://vimeo.com/123', 'description' => null]],
        'photos' => [],
        'sections' => [['heading' => 'Teaching Experience', 'entries' => []]],
    ]]);

    $this->get('/outreach')
        ->assertOk()
        ->assertDontSee('Broken Video')
        ->assertDontSee('<h3>Videos</h3>', false)
        ->assertDontSee('bxslider"', false)
        ->assertDontSee('Teaching Experience');
});

test('outreach list entries are escaped', function (): void {
    OutreachPage::factory()->create(['content' => [
        'sections' => [['heading' => 'Talks <b>', 'entries' => [
            ['date' => null, 'description' => '<script>alert(1)</script>', 'link' => null],
        ]]],
    ]]);

    $this->get('/outreach')
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
});
