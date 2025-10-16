<?php

declare(strict_types=1);

use App\Models\Pages\PublicationsPage;
use App\Models\Pages\ResearchPage;
use App\Models\Publication;
use App\Models\Research;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('research created in admin appears on frontend research page', function () {
    // Arrange: Create required data
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create a research project
    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Test Research Project',
        'description' => 'This is a test research project description.',
    ]);

    // Act & Assert: Visit frontend and verify research appears
    $this->visit('/research')
        ->assertSee('Research')
        ->assertSee('Test Research Project')
        ->assertSee('This is a test research project description.');
});

test('research edited in admin reflects changes on frontend', function () {
    // Arrange: Create required data
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Original Project Name',
        'description' => 'Original description.',
    ]);

    // Act: Update research project
    $research->update([
        'project_name' => 'Updated Project Name',
        'description' => 'Updated description with new information.',
    ]);

    // Assert: Visit frontend and verify updated content appears
    $this->visit('/research')
        ->assertSee('Updated Project Name')
        ->assertSee('Updated description with new information.')
        ->assertDontSee('Original Project Name')
        ->assertDontSee('Original description.');
});

test('publication created in admin appears on frontend publications page', function () {
    // Arrange: Create required data
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Publications',
            'meta_description' => 'Our publications',
        ]
    );

    // Create a publication
    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Test Publication Title',
        'authors' => 'Smith, J., Doe, A.',
        'publication_name' => 'Test Journal',
        'published' => true,
    ]);

    // Act & Assert: Visit frontend and verify publication appears
    $this->visit('/publications')
        ->assertSee('Publications')
        ->assertSee('Test Publication Title')
        ->assertSee('Smith, J., Doe, A.')
        ->assertSee('Test Journal');
});

test('publication edited in admin reflects changes on frontend', function () {
    // Arrange: Create required data
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Publications',
            'meta_description' => 'Our publications',
        ]
    );

    $publication = Publication::factory()->create([
        'page_id' => $publicationsPage->id,
        'title' => 'Original Publication Title',
        'authors' => 'Original Authors',
        'publication_name' => 'Original Journal',
        'published' => true,
    ]);

    // Act: Update publication
    $publication->update([
        'title' => 'Updated Publication Title',
        'authors' => 'Updated Authors',
        'publication_name' => 'Updated Journal',
    ]);

    // Assert: Visit frontend and verify updated content appears
    $this->visit('/publications')
        ->assertSee('Updated Publication Title')
        ->assertSee('Updated Authors')
        ->assertSee('Updated Journal')
        ->assertDontSee('Original Publication Title');
});

test('research page content edited in admin appears on frontend', function () {
    // Arrange: Create required data
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Update page content
    $researchPage->intro = 'This is the updated research page introduction text.';
    $researchPage->save();

    // Act & Assert: Visit frontend and verify updated page content appears
    $this->visit('/research')
        ->assertSee('This is the updated research page introduction text.');
});

test('publications page shows correct meta information from admin', function () {
    // Arrange: Create required data
    $publicationsPage = PublicationsPage::firstOrCreate(
        ['slug' => 'publications'],
        [
            'title' => 'Publications',
            'meta_title' => 'Our Scientific Publications',
            'meta_description' => 'Browse our peer-reviewed research publications',
        ]
    );

    // Act & Assert: Visit frontend and verify meta information
    $this->visit('/publications')
        ->assertTitle('Our Scientific Publications');
});

test('deleted research project no longer appears on frontend', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Project To Be Deleted',
        'description' => 'This project will be deleted.',
    ]);

    // First verify it appears
    $this->visit('/research')
        ->assertSee('Project To Be Deleted');

    // Act: Delete the research project
    $research->delete();

    // Assert: Visit frontend and verify it no longer appears
    $this->visit('/research')
        ->assertDontSee('Project To Be Deleted')
        ->assertDontSee('This project will be deleted.');
});

test('multiple research projects created in admin all appear on frontend in correct section', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'First Research Project',
        'description' => 'Description of first project.',
    ]);

    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Second Research Project',
        'description' => 'Description of second project.',
    ]);

    $research3 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Third Research Project',
        'description' => 'Description of third project.',
    ]);

    // Act & Assert: Visit frontend and verify all research projects appear
    $this->visit('/research')
        ->assertSee('First Research Project')
        ->assertSee('Description of first project.')
        ->assertSee('Second Research Project')
        ->assertSee('Description of second project.')
        ->assertSee('Third Research Project')
        ->assertSee('Description of third project.');
});
