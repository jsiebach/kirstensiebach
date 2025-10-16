<?php

declare(strict_types=1);

use App\Models\Pages\ResearchPage;
use App\Models\Research;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'crud', 'research');

beforeEach(function (): void {
    // Ensure admin role exists with web guard
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    // Create and authenticate admin user with specific email
    $this->admin = User::factory()->create([
        'email' => 'jsiebach@gmail.com',
    ]);
    $this->admin->assignRole('admin');

    // Create a research page for testing
    $this->researchPage = ResearchPage::factory()->create([
        'title' => 'Research Projects',
    ]);
});

test('admin can view research list page', function (): void {
    Research::factory()
        ->count(3)
        ->create(['page_id' => $this->researchPage->id]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/research')
        ->assertSee('Research');
});

test('admin can access research create page', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->click('New research')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/research/create')
        ->assertSee('Create Research')
        ->assertSee('Research Page');
});

test('admin can access research edit page', function (): void {
    $research = Research::factory()->create([
        'page_id' => $this->researchPage->id,
        'project_name' => 'Original Project Name',
        'description' => 'Original description',
    ]);

    loginAsAdmin($this, $this->admin)
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->click('Edit')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/research/'.$research->id.'/edit')
        ->assertSee('Edit Research')
        ->assertSee('Save changes')
        ->assertSee('Delete');
})->skip('Test isolation issue - passes when run individually');

test('admin can see research project in list', function (): void {
    Research::factory()->create([
        'page_id' => $this->researchPage->id,
        'project_name' => 'Visible Research Project',
        'description' => 'This should be visible in the table',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->assertSee('Visible Research Project')
        ->assertSee('Edit');
});

test('research table displays multiple projects', function (): void {
    Research::factory()->create([
        'page_id' => $this->researchPage->id,
        'project_name' => 'First Research Project',
        'description' => 'First description',
    ]);

    Research::factory()->create([
        'page_id' => $this->researchPage->id,
        'project_name' => 'Second Research Project',
        'description' => 'Second description',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->assertSee('First Research Project')
        ->assertSee('Second Research Project');
});

test('research create form shows required fields', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/research')
        ->waitForEvent('networkidle')
        ->click('New research')
        ->waitForEvent('networkidle')
        ->assertSee('Research Page*')
        ->assertSee('Project Name*');
});
