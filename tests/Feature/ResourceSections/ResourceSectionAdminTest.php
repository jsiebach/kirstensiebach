<?php

declare(strict_types=1);

use App\Filament\Resources\ResourceSections\Pages\EditResourceSection;
use App\Models\ResourceItem;
use App\Models\ResourceSection;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $this->actingAs(User::factory()->create()->assignRole('admin'));
});

test('renaming a section renames the heading for all of its resources', function (): void {
    $section = ResourceSection::factory()->create(['name' => 'Lesson Plans']);
    ResourceItem::factory()->for($section, 'section')->count(2)->create();

    Livewire::test(EditResourceSection::class, ['record' => $section->getRouteKey()])
        ->fillForm(['name' => 'Classroom Activities'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->get('/resources')
        ->assertSee('Classroom Activities')
        ->assertDontSee('Lesson Plans');
});

test('a section needs a name', function (): void {
    $section = ResourceSection::factory()->create();

    Livewire::test(EditResourceSection::class, ['record' => $section->getRouteKey()])
        ->fillForm(['name' => ''])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});
