<?php

declare(strict_types=1);

use App\Models\Pages\ResearchPage;
use App\Models\Research;

test('research project can be created with valid data', function (): void {
    // Create ResearchPage first
    $researchPage = ResearchPage::factory()->create([
        'slug' => 'research',
    ]);

    // Create research project using factory
    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Mars Surface Analysis',
        'description' => 'Study of Martian surface composition',
    ]);

    // Assert research project was created
    expect($research->id)->toBeInt();
    expect($research->project_name)->toBe('Mars Surface Analysis');
    expect($research->description)->toBe('Study of Martian surface composition');
    expect($research->sort_order)->toBeInt();
});

test('research project can have image uploaded', function (): void {
    $researchPage = ResearchPage::factory()->create([
        'slug' => 'research',
    ]);

    // Create research project with image using factory state
    $research = Research::factory()->withImage()->create([
        'page_id' => $researchPage->id,
    ]);

    // Assert image was set
    expect($research->image)->toBeString();
    expect($research->image)->toContain('images/research-');
});

test('research projects can be ordered by sort order', function (): void {
    $researchPage = ResearchPage::factory()->create([
        'slug' => 'research',
    ]);

    // Create multiple research projects
    $first = Research::factory()->create([
        'page_id' => $researchPage->id,
        'sort_order' => 1,
        'project_name' => 'Project A',
    ]);

    $second = Research::factory()->create([
        'page_id' => $researchPage->id,
        'sort_order' => 2,
        'project_name' => 'Project B',
    ]);

    // Query ordered research projects (ascending)
    $orderedProjects = Research::ordered('asc')->get();

    // Assert correct ordering
    expect($orderedProjects->first()->sort_order)->toBe(1);
    expect($orderedProjects->last()->sort_order)->toBe(2);
});
