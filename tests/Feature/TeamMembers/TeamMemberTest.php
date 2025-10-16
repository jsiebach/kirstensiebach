<?php

declare(strict_types=1);

use App\Models\Pages\LabPage;
use App\Models\TeamMember;

test('team member can be created using factory', function (): void {
    $labPage = LabPage::factory()->create([
        'slug' => 'lab',
    ]);

    $teamMember = TeamMember::factory()->create([
        'page_id' => $labPage->id,
        'name' => 'Dr. Jane Smith',
        'email' => 'jane.smith@university.edu',
    ]);

    expect($teamMember->id)->toBeInt();
    expect($teamMember->name)->toBe('Dr. Jane Smith');
    expect($teamMember->email)->toBe('jane.smith@university.edu');
    expect($teamMember->profile_picture)->toBeString();
});

test('team member alumni status can be set', function (): void {
    $labPage = LabPage::factory()->create([
        'slug' => 'lab',
    ]);

    // Create active team member
    $active = TeamMember::factory()->active()->create([
        'page_id' => $labPage->id,
    ]);

    // Create alumni member
    $alumni = TeamMember::factory()->alumni()->create([
        'page_id' => $labPage->id,
    ]);

    expect($active->alumni)->toBeFalse();
    expect($alumni->alumni)->toBeTrue();
});
