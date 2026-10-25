<?php

declare(strict_types=1);

use App\Models\Pages\HomePage;

test('homepage renders with json-ld structured data', function (): void {
    HomePage::create([
        'title' => 'Home',
        'slug' => 'home',
        'meta_title' => 'Home - Test Site',
        'meta_description' => 'Test home page',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('"@context": "http://www.schema.org"', false);
});
