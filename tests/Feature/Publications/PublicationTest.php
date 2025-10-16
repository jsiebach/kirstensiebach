<?php

declare(strict_types=1);

use App\Models\Pages\PublicationsPage;
use App\Models\Publication;

test('publication can be created with required fields', function (): void {
    $publicationsPage = PublicationsPage::factory()->create([
        'slug' => 'publications',
    ]);

    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Mars Mineralogy Study',
        'authors' => 'Doe, J., Smith, A.',
        'publication_name' => 'Journal of Planetary Science',
        'published' => true,
        'doi' => '10.1234/mars-study-2024',
    ]);

    expect($publication->id)->toBeInt();
    expect($publication->title)->toBe('Mars Mineralogy Study');
    expect($publication->authors)->toBe('Doe, J., Smith, A.');
    expect($publication->published)->toBeTrue();
    expect($publication->doi)->toBe('10.1234/mars-study-2024');
});

test('publications are ordered by date published descending', function (): void {
    $publicationsPage = PublicationsPage::factory()->create([
        'slug' => 'publications',
    ]);

    // Create publications with different dates
    $older = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'date_published' => '2022-01-01',
    ]);

    $newer = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'date_published' => '2024-01-01',
    ]);

    // Query publications (should be ordered by date_published desc due to global scope)
    $publications = Publication::all();

    expect($publications->first()->id)->toBe($newer->id);
    expect($publications->last()->id)->toBe($older->id);
});

test('publication date is cast to carbon instance', function (): void {
    $publicationsPage = PublicationsPage::factory()->create([
        'slug' => 'publications',
    ]);

    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'date_published' => '2024-06-15',
    ]);

    expect($publication->date_published)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($publication->date_published->format('Y-m-d'))->toBe('2024-06-15');
});
