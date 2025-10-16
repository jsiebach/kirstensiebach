<?php

declare(strict_types=1);

use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\Publication;
use App\Models\Research;

test('markdown in research description renders as HTML on frontend', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research with markdown content
    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Markdown Test Project',
        'description' => 'This is **bold text** and this is *italic text* in markdown.',
    ]);

    // Act & Assert: Visit frontend and verify markdown renders as HTML
    $page = $this->visit('/research');

    // Verify project name appears
    $page->assertSee('Markdown Test Project');

    // Verify markdown is rendered (bold and italic should be converted to HTML)
    // Note: The exact HTML tags depend on the markdown parser, typically <strong> and <em>
    $pageHtml = $page->text('body');
    expect($pageHtml)->toContain('bold text');
    expect($pageHtml)->toContain('italic text');

    // Verify raw markdown syntax is NOT visible
    expect($pageHtml)->not->toContain('**bold text**');
    expect($pageHtml)->not->toContain('*italic text*');
});

test('markdown links in publication abstract render correctly on frontend', function () {
    // Arrange: Create required data
    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Publications',
            'meta_description' => 'Our publications',
        ]
    );

    // Create publication with markdown link in abstract
    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Publication With Link',
        'authors' => 'Smith, J.',
        'publication_name' => 'Test Journal',
        'published' => true,
        'abstract' => 'For more information, visit [our website](https://example.com).',
    ]);

    // Act & Assert: Visit frontend and verify markdown link renders
    $page = $this->visit('/publications');

    // Verify publication appears
    $page->assertSee('Publication With Link');

    // Verify the link text appears (even if we need to expand the abstract)
    // The abstract may be hidden initially, so we check the page content
    $pageHtml = $page->text('body');

    // Verify raw markdown link syntax is NOT visible
    expect($pageHtml)->not->toContain('[our website]');
    expect($pageHtml)->not->toContain('](https://example.com)');
});

test('markdown formatting with multiple styles renders correctly on frontend', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research with complex markdown
    $markdown = <<<'MARKDOWN'
This research includes:

- **Bold text** for emphasis
- *Italic text* for styling
- [A link](https://example.com) for reference

The project focuses on ***both bold and italic*** formatting.
MARKDOWN;

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Complex Markdown Project',
        'description' => $markdown,
    ]);

    // Act & Assert: Visit frontend
    $page = $this->visit('/research')
        ->waitForText('Complex Markdown Project');
    $pageText = $page->text('body');

    // Verify project appears
    $page->assertSee('Complex Markdown Project');

    // Verify markdown is processed (raw markdown should not be visible)
    expect($pageText)->not->toContain('**Bold text**');
    expect($pageText)->not->toContain('*Italic text*');
    expect($pageText)->not->toContain('[A link]');

    // Verify rendered content appears
    expect($pageText)->toContain('Bold text');
    expect($pageText)->toContain('Italic text');
    expect($pageText)->toContain('A link');
});

test('markdown in publication authors field renders correctly on frontend', function () {
    // Arrange: Create required data
    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Publications',
            'meta_description' => 'Our publications',
        ]
    );

    // Create publication with markdown in authors field
    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Test Publication',
        'authors' => 'Smith, J., **Doe, A.** (corresponding author), Jones, K.',
        'publication_name' => 'Science Journal',
        'published' => true,
    ]);

    // Act & Assert: Visit frontend
    $page = $this->visit('/publications')
        ->waitForText('Test Publication');
    $pageText = $page->text('body');

    // Verify publication appears
    $page->assertSee('Test Publication');

    // Verify authors field content appears
    expect($pageText)->toContain('Smith, J.');
    expect($pageText)->toContain('Doe, A.');
    expect($pageText)->toContain('Jones, K.');

    // Verify markdown in authors is processed
    expect($pageText)->not->toContain('**Doe, A.**');
});
