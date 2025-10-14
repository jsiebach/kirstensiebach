<?php

namespace Tests\Feature;

use App\Models\Pages\HomePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PageResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test HomePage schemaless attribute saving and loading.
     */
    public function test_homepage_schemaless_attributes_save_and_load(): void
    {
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
        $this->assertEquals('Welcome to my site', $homePage->content->tagline);
        $this->assertEquals('I am a test user', $homePage->content->profile_summary);
    }

    /**
     * Test image upload to page.
     */
    public function test_page_image_upload(): void
    {
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
        $this->assertEquals($path, $homePage->content->banner);

        // Verify file exists
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test SEO fields save correctly.
     */
    public function test_seo_fields_save_correctly(): void
    {
        $page = HomePage::create([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'meta_title' => 'Test Meta Title',
            'meta_description' => 'This is a test meta description for SEO.',
        ]);

        // Assert SEO fields saved
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'title' => 'Test Page',
            'slug' => 'test-page',
            'meta_title' => 'Test Meta Title',
            'meta_description' => 'This is a test meta description for SEO.',
        ]);
    }

    /**
     * Test conditional Call to Action fields.
     */
    public function test_homepage_conditional_cta_fields(): void
    {
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
        $this->assertTrue($homePage->content->add_cta_banner);
        $this->assertEquals('Join our research team!', $homePage->content->cta);
        $this->assertEquals('https://example.com/join', $homePage->content->action_link);
        $this->assertEquals('Apply Now', $homePage->content->action_text);
    }
}
