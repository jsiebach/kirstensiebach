<?php

declare(strict_types=1);

use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $this->actingAs(User::factory()->create()->assignRole('admin'));
});

test('admin can create a team member with a phone-sized jpg headshot', function (): void {
    Storage::fake('public');

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'name' => 'Jane Seeger',
            'email' => 'jane@example.com',
            'bio' => 'Mars geologist.',
            'profile_picture' => UploadedFile::fake()->image('Seeger_headshot.JPG', 3024, 4032)->size(1320),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $teamMember = TeamMember::where('email', 'jane@example.com')->sole();
    Storage::disk('public')->assertExists($teamMember->profile_picture);
});
