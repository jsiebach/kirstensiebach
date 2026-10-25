<?php

declare(strict_types=1);

use App\Models\Press;

test('press item can be created with required fields', function (): void {
    $press = Press::factory()->create([
        'title' => 'NASA Features Mars Research',
        'link' => 'https://example.com/nasa-mars-research',
        'date' => '2024-03-15',
    ]);

    expect($press->id)->toBeInt();
    expect($press->title)->toBe('NASA Features Mars Research');
    expect($press->link)->toBe('https://example.com/nasa-mars-research');
});

test('press items are ordered by date descending', function (): void {
    $older = Press::factory()->create([
        'date' => '2023-01-01',
    ]);

    $newer = Press::factory()->create([
        'date' => '2024-01-01',
    ]);

    $pressItems = Press::all();

    expect($pressItems->first()->id)->toBe($newer->id);
    expect($pressItems->last()->id)->toBe($older->id);
});
