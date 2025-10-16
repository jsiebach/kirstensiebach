<?php

declare(strict_types=1);

use App\Models\Pages\OutreachPage;
use App\Models\Press;

test('press item can be created with required fields', function (): void {
    $outreachPage = OutreachPage::factory()->create([
        'slug' => 'outreach',
    ]);

    $press = Press::factory()->create([
        'page_id' => $outreachPage->id,
        'title' => 'NASA Features Mars Research',
        'link' => 'https://example.com/nasa-mars-research',
        'date' => '2024-03-15',
    ]);

    expect($press->id)->toBeInt();
    expect($press->title)->toBe('NASA Features Mars Research');
    expect($press->link)->toBe('https://example.com/nasa-mars-research');
});

test('press items are ordered by date descending', function (): void {
    $outreachPage = OutreachPage::factory()->create([
        'slug' => 'outreach',
    ]);

    $older = Press::factory()->create([
        'page_id' => $outreachPage->id,
        'date' => '2023-01-01',
    ]);

    $newer = Press::factory()->create([
        'page_id' => $outreachPage->id,
        'date' => '2024-01-01',
    ]);

    $pressItems = Press::all();

    expect($pressItems->first()->id)->toBe($newer->id);
    expect($pressItems->last()->id)->toBe($older->id);
});
