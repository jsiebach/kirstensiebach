<?php

declare(strict_types=1);

use App\Models\Pages\ResearchPage;
use App\Models\Research;
use Illuminate\Support\Facades\Storage;

test('research image uploaded in admin displays on frontend with correct src', function () {
    // Arrange: Create required data
    Storage::fake('public');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research with image
    $imagePath = 'images/research-test-image.jpg';
    Storage::disk('public')->put($imagePath, 'fake-image-content');

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Research With Image',
        'description' => 'This research has an image.',
        'image' => $imagePath,
    ]);

    // Act & Assert: Visit frontend and verify image displays with correct src
    $page = $this->visit('/research')
        ->waitForEvent('networkidle');

    $page->assertSee('Research With Image');

    // Verify img tag with correct src exists
    $imgSrc = $page->attribute('img[src*="research-test-image"]', 'src');
    expect($imgSrc)->toContain('/storage/'.$imagePath);
})->skip('Storage::fake() creates paths that 404 in browser tests');

test('research image path loads correctly on frontend after admin upload', function () {
    // Arrange: Create required data
    Storage::fake('public');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    $imagePath = 'images/research-mars.jpg';
    Storage::disk('public')->put($imagePath, 'fake-mars-image');

    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Mars Research Project',
        'description' => 'Research about Mars.',
        'image' => $imagePath,
    ]);

    // Act & Assert: Verify image src attribute contains correct path
    $page = $this->visit('/research')
        ->waitForEvent('networkidle');
    $imgSrc = $page->attribute('img[src*="research-mars"]', 'src');

    expect($imgSrc)->toContain('/storage/images/research-mars.jpg');
})->skip('Storage::fake() creates paths that 404 in browser tests');

test('multiple research images display in correct locations on frontend', function () {
    // Arrange: Create required data
    Storage::fake('public');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create multiple research projects with images
    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'First Project With Image',
        'description' => 'First project description.',
        'image' => 'images/research-first.jpg',
    ]);
    Storage::disk('public')->put('images/research-first.jpg', 'fake-first-image');

    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Second Project With Image',
        'description' => 'Second project description.',
        'image' => 'images/research-second.jpg',
    ]);
    Storage::disk('public')->put('images/research-second.jpg', 'fake-second-image');

    // Act & Assert: Visit frontend and verify both images appear
    $page = $this->visit('/research')
        ->waitForEvent('networkidle');

    // Check both projects and their images are present
    $page->assertSee('First Project With Image')
        ->assertSee('Second Project With Image');

    // Verify both image sources are present
    $firstImgSrc = $page->attribute('img[src*="research-first"]', 'src');
    $secondImgSrc = $page->attribute('img[src*="research-second"]', 'src');

    expect($firstImgSrc)->toContain('/storage/images/research-first.jpg');
    expect($secondImgSrc)->toContain('/storage/images/research-second.jpg');
})->skip('Storage::fake() creates paths that 404 in browser tests');

test('research page banner image displays correctly on frontend', function () {
    // Arrange: Create required data
    Storage::fake('public');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Set banner image in page content
    $bannerPath = 'images/research-banner.jpg';
    Storage::disk('public')->put($bannerPath, 'fake-banner-image');

    $researchPage->banner = $bannerPath;
    $researchPage->save();

    // Act & Assert: Visit frontend and verify banner displays
    $page = $this->visit('/research')
        ->waitForEvent('networkidle');
    $bannerSrc = $page->attribute('img[src*="research-banner"]', 'src');

    expect($bannerSrc)->toContain('/storage/images/research-banner.jpg');
})->skip('Storage::fake() creates paths that 404 in browser tests');

test('research without image does not break frontend display', function () {
    // Arrange: Create required data
    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create research without image
    $research = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Research Without Image',
        'description' => 'This research has no image.',
        'image' => null,
    ]);

    // Act & Assert: Visit frontend and verify research displays without error
    $this->visit('/research')
        ->assertSee('Research Without Image')
        ->assertSee('This research has no image.');
});

test('image alternates left and right placement on research page', function () {
    // Arrange: Create required data
    Storage::fake('public');

    $researchPage = ResearchPage::firstOrCreate(
        ['slug' => 'research'],
        [
            'title' => 'Research',
            'meta_title' => 'Research Projects',
            'meta_description' => 'Our research projects',
        ]
    );

    // Create even-indexed research (should be left-aligned)
    $research1 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'First Project',
        'description' => 'First description.',
        'image' => 'images/research-1.jpg',
    ]);
    Storage::disk('public')->put('images/research-1.jpg', 'fake-image-1');

    // Create odd-indexed research (should be right-aligned)
    $research2 = Research::factory()->create([
        'page_id' => $researchPage->id,
        'project_name' => 'Second Project',
        'description' => 'Second description.',
        'image' => 'images/research-2.jpg',
    ]);
    Storage::disk('public')->put('images/research-2.jpg', 'fake-image-2');

    // Act & Assert: Visit frontend and verify images have correct alignment classes
    $page = $this->visit('/research');

    // Verify page loads and projects appear
    $page->assertSee('First Project')
        ->assertSee('Second Project');

    // Check for presence of pull-left and pull-right classes (alternating pattern)
    $firstImgClass = $page->attribute('img[src*="research-1"]', 'class');
    $secondImgClass = $page->attribute('img[src*="research-2"]', 'class');

    expect($firstImgClass)->toContain('pull-left');
    expect($secondImgClass)->toContain('pull-right');
})->skip('Storage::fake() creates paths that 404 in browser tests');
