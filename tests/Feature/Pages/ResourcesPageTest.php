<?php

declare(strict_types=1);

use App\Models\Pages\HomePage;
use App\Models\Pages\ResourcesPage;
use App\Models\ResourceItem;
use App\Models\ResourceSection;
use Illuminate\Support\Facades\Storage;

// The Resources page row itself is created, unlisted, by its migration

test('resources page shows each kind of resource under its section in admin order', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('resources/lesson.pdf', str_repeat('x', 2048));
    $readingSection = ResourceSection::factory()->create(['name' => 'Further Reading', 'description' => null]);
    $classroomSection = ResourceSection::factory()->create(['name' => 'For the Classroom', 'description' => 'Ready to use with grades 6-8.']);
    ResourceSection::setNewOrder([$classroomSection->id, $readingSection->id]);

    $link = ResourceItem::factory()->for($readingSection, 'section')->create(['title' => 'NASA Mars Site', 'url' => 'https://mars.nasa.gov']);
    $document = ResourceItem::factory()->for($classroomSection, 'section')->document('resources/lesson.pdf')->create(['title' => 'Crater Lesson Plan', 'file_name' => 'Crater Lesson.pdf']);
    $video = ResourceItem::factory()->for($classroomSection, 'section')->video('iMt7azVQsCs')->create(['title' => 'Gale Crater Video']);
    $image = ResourceItem::factory()->image('resources/crater.jpg')->create(['title' => 'Crater Picture']);
    ResourceItem::setNewOrder([$image->id, $video->id, $document->id, $link->id]);

    $this->get('/resources')
        ->assertOk()
        ->assertSeeInOrder([
            'Crater Picture', 'src="/storage/resources/crater.jpg"',
            'For the Classroom', 'Ready to use with grades 6-8.',
            'Gale Crater Video', 'data-youtube-id="iMt7azVQsCs"',
            'Crater Lesson Plan', 'href="/storage/resources/lesson.pdf" download="Crater Lesson.pdf"', '(PDF, 2 KB)',
            'Further Reading',
            'NASA Mars Site', 'href="https://mars.nasa.gov"',
        ], false);
});

test('resources page leaves out sections with no resources', function (): void {
    ResourceSection::factory()->create(['name' => 'Empty Section']);
    ResourceItem::factory()->create();

    $this->get('/resources')
        ->assertOk()
        ->assertDontSee('Empty Section');
});

test('resources page shows a placeholder before anything is added', function (): void {

    $this->get('/resources')
        ->assertOk()
        ->assertSee('Resources are on their way.');
});

test('unlisted resources page stays out of the menu, the sitemap and search engines', function (): void {
    HomePage::create(['title' => 'Home', 'slug' => 'home', 'meta_title' => 'Home']);

    $this->get('/resources')
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex">', false)
        ->assertDontSee('href="/resources"', false);

    $this->get('/sitemap.xml')->assertDontSee(url('/resources'));
});

test('listed resources page appears in the menu and sitemap and can be indexed', function (): void {
    HomePage::create(['title' => 'Home', 'slug' => 'home', 'meta_title' => 'Home']);
    $page = ResourcesPage::sole();
    $page->show_in_menu = true;
    $page->save();

    $this->get('/resources')
        ->assertOk()
        ->assertDontSee('<meta name="robots" content="noindex">', false)
        ->assertSee('href="/resources"', false);

    $this->get('/sitemap.xml')->assertSee('<loc>'.url('/resources').'</loc>', false);
});

test('resource descriptions are escaped', function (): void {
    ResourceItem::factory()->create(['description' => '<img src=x onerror=alert(1)>']);

    $this->get('/resources')
        ->assertOk()
        ->assertDontSee('<img src=x onerror=alert(1)>', false);
});
