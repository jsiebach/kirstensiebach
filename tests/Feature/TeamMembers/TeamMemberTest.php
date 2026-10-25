<?php

declare(strict_types=1);

use App\Models\TeamMember;

test('team member can be created using factory', function (): void {
    $teamMember = TeamMember::factory()->create([
        'name' => 'Dr. Jane Smith',
        'email' => 'jane.smith@university.edu',
    ]);

    expect($teamMember->id)->toBeInt();
    expect($teamMember->name)->toBe('Dr. Jane Smith');
    expect($teamMember->email)->toBe('jane.smith@university.edu');
    expect($teamMember->profile_picture)->toBeString();
});

test('team member alumni status can be set', function (): void {
    // Create active team member
    $active = TeamMember::factory()->active()->create([
    ]);

    // Create alumni member
    $alumni = TeamMember::factory()->alumni()->create([
    ]);

    expect($active->alumni)->toBeFalse();
    expect($alumni->alumni)->toBeTrue();
});
