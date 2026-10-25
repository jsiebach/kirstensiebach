<?php

declare(strict_types=1);

use App\Models\Pages\OutreachPage;
use Illuminate\Support\Facades\Storage;

function runOutreachContentMigration(): void
{
    (require database_path('migrations/2026_10_25_191154_move_outreach_page_content_into_the_admin.php'))->up();
}

test('outreach page content that used to be hard-coded is copied into the page record', function (): void {
    Storage::fake('public');
    $page = OutreachPage::factory()->create(['content' => []]);

    runOutreachContentMigration();

    $page->refresh();
    expect(collect($page->videos)->pluck('youtube_url')->all())->toBe([
        'https://www.youtube.com/watch?v=iMt7azVQsCs',
        'https://www.youtube.com/watch?v=nQeJ945O-_g',
        'https://www.youtube.com/watch?v=NfpzGW2t_KU',
    ]);
    expect(collect($page->sections)->mapWithKeys(fn (array $section): array => [$section['heading'] => count($section['entries'])])->all())->toBe([
        'Recent Keynote Talks' => 18,
        'Additional Talks' => 29,
        'Teaching Experience' => 9,
        'Additional outreach experience' => 9,
    ]);
    expect($page->photos)->toHaveCount(6);
    Storage::disk('public')->assertExists([$page->intro_image, ...$page->photos]);
});

test('outreach page content already edited in the admin is left alone', function (): void {
    Storage::fake('public');
    $page = OutreachPage::factory()->create(['content' => ['intro' => 'Already edited']]);

    runOutreachContentMigration();

    expect($page->refresh()->content->toArray())->toBe(['intro' => 'Already edited']);
});
