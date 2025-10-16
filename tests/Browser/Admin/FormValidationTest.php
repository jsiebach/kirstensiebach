<?php

declare(strict_types=1);

use App\Models\Pages\OutreachPage;
use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'validation');

beforeEach(function (): void {
    // Ensure admin role exists with web guard
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    // Create and authenticate admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    // Create test pages
    $this->researchPage = ResearchPage::factory()->create([
        'title' => 'Research Projects',
    ]);

    $this->publicationsPage = PublicationsPage::factory()->create([
        'title' => 'Publications',
    ]);

    $this->outreachPage = OutreachPage::factory()->create([
        'title' => 'Outreach',
    ]);
});

test('research form shows required field indicators', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->click('New research')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/research/create')
        ->assertSee('Research Page*')
        ->assertSee('Project Name*');
});

test('publication form shows all required fields', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->click('New publication')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/publications/create')
        ->assertSee('Publications Page*')
        ->assertSee('Publication Title*')
        ->assertSee('Journal/Conference Name*')
        ->assertSee('Authors*')
        ->assertSee('Date Published*');
});

test('press form shows required field indicators', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->click('New press')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/presses/create')
        ->assertSee('Outreach Page*')
        ->assertSee('Title*')
        ->assertSee('Link*')
        ->assertSee('Date*');
});
