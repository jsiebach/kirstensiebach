<?php

declare(strict_types=1);

use App\Models\Pages\OutreachPage;
use App\Models\Press;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'crud', 'press');

beforeEach(function (): void {
    // Ensure admin role exists with web guard
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    // Create and authenticate admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    // Create an outreach page for testing
    $this->outreachPage = OutreachPage::factory()->create([
        'title' => 'Outreach & Press',
    ]);
});

test('admin can view press list page', function (): void {
    Press::factory()
        ->count(3)
        ->create(['page_id' => $this->outreachPage->id]);

    $this->visit('/admin/login')
        ->waitForText('Sign in')
        ->type('#form\\.email', $this->admin->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/presses')
        ->assertSee('Press');
});

test('admin can access press create page', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->click('New press')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/presses/create')
        ->assertSee('Create Press')
        ->assertSee('Outreach Page');
});

test('admin can access press edit page', function (): void {
    $press = Press::factory()->create([
        'page_id' => $this->outreachPage->id,
        'title' => 'Original Press Title',
        'link' => 'https://example.com/original',
        'date' => '2023-05-10',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->click('Edit')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/presses/'.$press->id.'/edit')
        ->assertSee('Edit Press')
        ->assertSee('Save changes')
        ->assertSee('Delete');
});

test('admin can see press item in list', function (): void {
    $press = Press::factory()->create([
        'page_id' => $this->outreachPage->id,
        'title' => 'Test Press Item',
        'link' => 'https://example.com/test',
        'date' => '2023-08-15',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->assertSee('Test Press Item')
        ->assertSee('Edit');
});

test('press table displays multiple items', function (): void {
    Press::factory()->create([
        'page_id' => $this->outreachPage->id,
        'title' => 'Newer Press Item',
        'link' => 'https://example.com/newer',
        'date' => '2024-06-01',
    ]);

    Press::factory()->create([
        'page_id' => $this->outreachPage->id,
        'title' => 'Older Press Item',
        'link' => 'https://example.com/older',
        'date' => '2023-06-01',
    ]);

    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->assertSee('Newer Press Item')
        ->assertSee('Older Press Item');
});

test('press create form shows required fields', function (): void {
    loginAsAdmin($this, $this->admin)
        ->assertPathIs('/admin')
        ->navigate('/admin/presses')
        ->waitForEvent('networkidle')
        ->click('New press')
        ->waitForEvent('networkidle')
        ->assertSee('Outreach Page*')
        ->assertSee('Title*')
        ->assertSee('Link*')
        ->assertSee('Date*');
});
