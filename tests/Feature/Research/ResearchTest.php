<?php

declare(strict_types=1);

use App\Models\Research;

test('research project can be created with valid data', function (): void {
    // Create research project using factory
    $research = Research::factory()->create([
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
    // Create research project with image using factory state
    $research = Research::factory()->withImage()->create([
    ]);

    // Assert image was set
    expect($research->image)->toBeString();
    expect($research->image)->toContain('images/research-');
});

test('research projects can be ordered by sort order', function (): void {
    // Create multiple research projects
    $first = Research::factory()->create([
        'sort_order' => 1,
        'project_name' => 'Project A',
    ]);

    $second = Research::factory()->create([
        'sort_order' => 2,
        'project_name' => 'Project B',
    ]);

    // Query ordered research projects (ascending)
    $orderedProjects = Research::ordered('asc')->get();

    // Assert correct ordering
    expect($orderedProjects->first()->sort_order)->toBe(1);
    expect($orderedProjects->last()->sort_order)->toBe(2);
});
