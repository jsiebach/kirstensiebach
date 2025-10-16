<?php

declare(strict_types=1);

use App\Models\Pages\PublicationsPage;
use App\Models\Publication;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'crud', 'publication');

beforeEach(function (): void {
    // Ensure admin role exists with web guard
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    // Create and authenticate admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    // Create a publications page for testing
    $this->publicationsPage = PublicationsPage::factory()->create([
        'title' => 'Publications',
    ]);
});

test('admin can view publications list page', function (): void {
    Publication::factory()
        ->count(3)
        ->create(['page_id' => $this->publicationsPage->id]);

    $this->visit('/admin/login')
        ->waitForText('Sign in')
        ->type('#form\\.email', $this->admin->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/publications')
        ->assertSee('Publications');
});

test('admin can access publication create page', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->click('New publication')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/publications/create')
        ->assertSee('Create Publication')
        ->assertSee('Publications Page')
        ->assertSee('Publication Title')
        ->assertSee('Journal/Conference Name');
});

test('admin can access publication edit page', function (): void {
    $publication = Publication::factory()->create([
        'page_id' => $this->publicationsPage->id,
        'title' => 'Original Publication Title',
        'publication_name' => 'Original Journal',
        'authors' => 'Original Authors',
        'date_published' => '2023-01-01',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->click('Edit')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/publications/'.$publication->id.'/edit')
        ->assertSee('Edit Publication')
        ->assertSee('Publications Page')
        ->assertSee('Save changes')
        ->assertSee('Delete');  // Delete button should be visible on edit page
});

test('admin can see publication in list', function (): void {
    $publication = Publication::factory()->create([
        'page_id' => $this->publicationsPage->id,
        'title' => 'Test Publication Item',
        'publication_name' => 'Test Journal',
        'authors' => 'Test Author',
        'date_published' => '2023-06-01',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->assertSee('Test Publication Item')
        ->assertSee('Test Journal')
        ->assertSee('Edit');  // Verify action is available
});

test('publications table displays all key fields', function (): void {
    Publication::factory()->create([
        'page_id' => $this->publicationsPage->id,
        'title' => 'Comprehensive Study Title',
        'publication_name' => 'Science Journal',
        'authors' => 'Dr. Jane Smith, Prof. John Doe',
        'date_published' => '2024-03-15',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->assertSee('Comprehensive Study Title')
        ->assertSee('Science Journal');
});

test('publication create form shows all required fields', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/publications')
        ->waitForEvent('networkidle')
        ->click('New publication')
        ->waitForEvent('networkidle')
        ->assertSee('Publications Page*')  // Asterisk indicates required
        ->assertSee('Publication Title*')
        ->assertSee('Journal/Conference Name*')
        ->assertSee('Authors*')
        ->assertSee('Date Published*');
});
