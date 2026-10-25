<?php

declare(strict_types=1);

use App\Enums\ResourceType;
use App\Filament\Resources\ResourceItems\Pages\CreateResourceItem;
use App\Filament\Resources\ResourceItems\Pages\EditResourceItem;
use App\Models\ResourceItem;
use App\Models\ResourceSection;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $this->actingAs(User::factory()->create()->assignRole('admin'));
});

test('admin can upload a document resource and its original file name is kept for downloads', function (): void {
    Storage::fake('public');
    $section = ResourceSection::factory()->create();

    Livewire::test(CreateResourceItem::class)
        ->fillForm([
            'type' => ResourceType::Document,
            'title' => 'Crater Lesson Plan',
            'resource_section_id' => $section->id,
            'file_path' => UploadedFile::fake()->create('Crater Lesson Plan.pdf', 500, 'application/pdf'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $item = ResourceItem::where('title', 'Crater Lesson Plan')->sole();
    expect($item->type)->toBe(ResourceType::Document)
        ->and($item->file_name)->toBe('Crater Lesson Plan.pdf')
        ->and($item->resource_section_id)->toBe($section->id);
    Storage::disk('public')->assertExists($item->file_path);
});

test('admin must attach a file to a document resource', function (): void {
    Livewire::test(CreateResourceItem::class)
        ->fillForm(['type' => ResourceType::Document, 'title' => 'Missing File'])
        ->call('create')
        ->assertHasFormErrors(['file_path' => 'required']);

    expect(ResourceItem::count())->toBe(0);
});

test('admin cannot save a video resource that is not a youtube link', function (): void {
    Livewire::test(CreateResourceItem::class)
        ->fillForm(['type' => ResourceType::Video, 'title' => 'Vimeo Video', 'url' => 'https://vimeo.com/123456789'])
        ->call('create')
        ->assertHasFormErrors(['url'])
        ->assertSee('Please paste a link to a YouTube video');

    expect(ResourceItem::count())->toBe(0);
});

test('admin can add a youtube video resource', function (): void {
    Livewire::test(CreateResourceItem::class)
        ->fillForm(['type' => ResourceType::Video, 'title' => 'Gale Crater', 'url' => 'https://youtu.be/iMt7azVQsCs'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(ResourceItem::sole()->youtubeId())->toBe('iMt7azVQsCs');
});

test('switching a document resource to a link removes the uploaded file', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('resources/old.pdf', 'pdf');
    $item = ResourceItem::factory()->document('resources/old.pdf')->create();

    Livewire::test(EditResourceItem::class, ['record' => $item->getRouteKey()])
        ->fillForm(['type' => ResourceType::Link, 'url' => 'https://mars.nasa.gov'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($item->refresh())
        ->file_path->toBeNull()
        ->url->toBe('https://mars.nasa.gov');
    Storage::disk('public')->assertMissing('resources/old.pdf');
});

test('deleting a resource removes its uploaded file', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('resources/old.pdf', 'pdf');
    $item = ResourceItem::factory()->document('resources/old.pdf')->create();

    $item->delete();

    Storage::disk('public')->assertMissing('resources/old.pdf');
});

test('deleting a section keeps its resources and moves them to the top of the page', function (): void {
    $section = ResourceSection::factory()->create();
    $item = ResourceItem::factory()->for($section, 'section')->create();

    $section->delete();

    expect($item->refresh()->resource_section_id)->toBeNull();
});
