<?php

declare(strict_types=1);

use App\Models\Pages\HomePage;
use App\Models\SocialLink;

function createHomePage(array $content = []): HomePage
{
    $homePage = HomePage::create([
        'title' => 'Home',
        'slug' => 'home',
        'meta_title' => 'Home - Test Site',
        'meta_description' => 'Test home page',
    ]);

    foreach ($content as $key => $value) {
        $homePage->content->{$key} = $value;
    }
    $homePage->save();

    return $homePage;
}

test('homepage renders valid json-ld structured data', function (): void {
    createHomePage(['profile_picture' => 'pages/profile.jpg']);

    $html = $this->get('/')->assertOk()->getContent();

    preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $match);
    $structuredData = json_decode($match[1] ?? '', true);

    expect($structuredData)->toBeArray()
        ->and($structuredData['@context'])->toBe('https://schema.org')
        ->and($structuredData['@type'])->toBe('Person')
        ->and($structuredData['image'])->toBe(url('/storage/pages/profile.jpg'))
        ->and($structuredData['jobTitle'])->toBe('Assistant Professor')
        ->and(implode(' ', $structuredData['sameAs']))->not->toContain('plus.google.com');
});

test('homepage lists social links in their admin sort order', function (): void {
    createHomePage();
    $second = SocialLink::factory()->create(['title' => 'Second Link']);
    $first = SocialLink::factory()->create(['title' => 'First Link']);

    // Created second, but moved to the top in the admin
    SocialLink::setNewOrder([$first->id, $second->id]);

    $this->get('/')
        ->assertOk()
        ->assertSeeInOrder(['First Link', 'Second Link']);
});
