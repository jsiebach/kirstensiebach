<?php

declare(strict_types=1);

use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\Publication;
use App\Models\Research;

test('research projects display in correct sort order on frontend', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research projects with specific sort order
    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'First Project',
        'description' => 'Should appear first.',
        'sort_order' => 1,
    ]);

    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Second Project',
        'description' => 'Should appear second.',
        'sort_order' => 2,
    ]);

    $research3 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Third Project',
        'description' => 'Should appear third.',
        'sort_order' => 3,
    ]);

    // Act: Visit frontend
    $page = $this->visit('/research')
        ->waitForText('First Project');

    // Assert: Verify all projects appear
    $page->assertSee('First Project')
        ->assertSee('Second Project')
        ->assertSee('Third Project');

    // Get page content to verify order
    $pageText = $page->text('body');
    $firstPos = strpos($pageText, 'First Project');
    $secondPos = strpos($pageText, 'Second Project');
    $thirdPos = strpos($pageText, 'Third Project');

    // Verify they appear in correct order
    expect($firstPos)->toBeLessThan($secondPos);
    expect($secondPos)->toBeLessThan($thirdPos);
});

test('research projects maintain order after sort_order is updated in admin', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research projects
    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Project A',
        'description' => 'Originally first.',
        'sort_order' => 1,
    ]);

    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Project B',
        'description' => 'Originally second.',
        'sort_order' => 2,
    ]);

    // Act: Reverse the order
    $research1->update(['sort_order' => 2]);
    $research2->update(['sort_order' => 1]);

    // Visit frontend
    $page = $this->visit('/research')
        ->waitForText('Project A');

    // Assert: Verify order is reversed
    $pageText = $page->text('body');
    $posA = strpos($pageText, 'Project A');
    $posB = strpos($pageText, 'Project B');

    // Project B should now appear before Project A
    expect($posB)->toBeLessThan($posA);
});

test('publications display in date order on frontend', function () {
    // Arrange: Create required data
    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Publications',
            'meta_description' => 'Our publications',
        ]
    );

    // Create publications with different dates (newest should appear first due to global scope)
    $pub1 = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Oldest Publication',
        'authors' => 'Smith, J.',
        'publication_name' => 'Journal A',
        'published' => true,
        'date_published' => '2020-01-01',
    ]);

    $pub2 = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Middle Publication',
        'authors' => 'Doe, A.',
        'publication_name' => 'Journal B',
        'published' => true,
        'date_published' => '2022-06-15',
    ]);

    $pub3 = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Newest Publication',
        'authors' => 'Jones, K.',
        'publication_name' => 'Journal C',
        'published' => true,
        'date_published' => '2024-03-20',
    ]);

    // Act: Visit frontend
    $page = $this->visit('/publications')
        ->waitForText('Newest Publication');

    // Assert: Verify all publications appear
    $page->assertSee('Oldest Publication')
        ->assertSee('Middle Publication')
        ->assertSee('Newest Publication');

    // Get page content to verify order (newest first)
    $pageText = $page->text('body');
    $newestPos = strpos($pageText, 'Newest Publication');
    $middlePos = strpos($pageText, 'Middle Publication');
    $oldestPos = strpos($pageText, 'Oldest Publication');

    // Verify newest appears first
    expect($newestPos)->toBeLessThan($middlePos);
    expect($middlePos)->toBeLessThan($oldestPos);
});

test('changing research sort order reflects immediately on frontend after refresh', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Priority Project',
        'description' => 'High priority research.',
        'sort_order' => 10,
    ]);

    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Standard Project',
        'description' => 'Standard research.',
        'sort_order' => 20,
    ]);

    // First visit: verify initial order
    $page1 = $this->visit('/research')
        ->waitForText('Priority Project');
    $pageText1 = $page1->text('body');
    $priorityPos1 = strpos($pageText1, 'Priority Project');
    $standardPos1 = strpos($pageText1, 'Standard Project');
    expect($priorityPos1)->toBeLessThan($standardPos1);

    // Act: Change sort order to reverse positions
    $research1->update(['sort_order' => 30]);
    $research2->update(['sort_order' => 5]);

    // Second visit: verify updated order
    $page2 = $this->visit('/research')
        ->waitForText('Standard Project');
    $pageText2 = $page2->text('body');
    $priorityPos2 = strpos($pageText2, 'Priority Project');
    $standardPos2 = strpos($pageText2, 'Standard Project');

    // Now Standard Project should appear before Priority Project
    expect($standardPos2)->toBeLessThan($priorityPos2);
});
