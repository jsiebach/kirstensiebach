<?php

declare(strict_types=1);

use App\Models\Pages\HomePage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('homepage schemaless attributes save and load', function (): void {
    // Create HomePage with schemaless content
    $homePage = HomePage::create([
        'title' => 'Home',
        'slug' => 'home',
        'meta_title' => 'Home - Test Site',
        'meta_description' => 'Test home page',
    ]);

    // Set schemaless attributes
    $homePage->content->tagline = 'Welcome to my site';
    $homePage->content->profile_summary = 'I am a test user';
    $homePage->save();

    // Reload from database
    $homePage = HomePage::find($homePage->id);

    // Assert schemaless attributes persisted
    expect($homePage->content->tagline)->toBe('Welcome to my site');
    expect($homePage->content->profile_summary)->toBe('I am a test user');
});

test('page image upload', function (): void {
    Storage::fake('public');

    $homePage = HomePage::create([
        'title' => 'Home',
        'slug' => 'home',
        'meta_title' => 'Home - Test Site',
    ]);

    // Simulate image upload
    $file = UploadedFile::fake()->image('banner.jpg');
    $path = $file->store('pages', 'public');

    // Store path in schemaless attribute
    $homePage->content->banner = $path;
    $homePage->save();

    // Reload and verify
    $homePage = HomePage::find($homePage->id);
    expect($homePage->content->banner)->toBe($path);

    // Verify file exists
    Storage::disk('public')->assertExists($path);
});

test('seo fields save correctly', function (): void {
    $page = HomePage::create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'meta_title' => 'Test Meta Title',
        'meta_description' => 'This is a test meta description for SEO.',
    ]);

    // Assert SEO fields saved
    expect($page->id)->toBeInt();
    expect($page->title)->toBe('Test Page');
    expect($page->slug)->toBe('test-page');
    expect($page->meta_title)->toBe('Test Meta Title');
    expect($page->meta_description)->toBe('This is a test meta description for SEO.');
});

test('homepage conditional cta fields', function (): void {
    $homePage = HomePage::create([
        'title' => 'Home',
        'slug' => 'home',
        'meta_title' => 'Home',
    ]);

    // Set CTA fields
    $homePage->content->add_cta_banner = true;
    $homePage->content->cta = 'Join our research team!';
    $homePage->content->action_link = 'https://example.com/join';
    $homePage->content->action_text = 'Apply Now';
    $homePage->save();

    // Reload and verify
    $homePage = HomePage::find($homePage->id);
    expect($homePage->content->add_cta_banner)->toBeTrue();
    expect($homePage->content->cta)->toBe('Join our research team!');
    expect($homePage->content->action_link)->toBe('https://example.com/join');
    expect($homePage->content->action_text)->toBe('Apply Now');
});
