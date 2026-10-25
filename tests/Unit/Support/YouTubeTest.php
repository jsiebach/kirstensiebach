<?php

declare(strict_types=1);

use App\Support\YouTube;

test('video id is read from each kind of youtube link', function (string $url): void {
    expect(YouTube::idFromUrl($url))->toBe('iMt7azVQsCs');
})->with([
    'watch link' => 'https://www.youtube.com/watch?v=iMt7azVQsCs',
    'watch link with extra parameters' => 'https://www.youtube.com/watch?feature=share&v=iMt7azVQsCs&t=42s',
    'mobile link' => 'https://m.youtube.com/watch?v=iMt7azVQsCs',
    'short share link' => 'https://youtu.be/iMt7azVQsCs?si=abc123',
    'embed link' => 'https://www.youtube.com/embed/iMt7azVQsCs',
    'privacy embed link' => 'https://www.youtube-nocookie.com/embed/iMt7azVQsCs',
    'shorts link' => 'https://www.youtube.com/shorts/iMt7azVQsCs',
    'live link' => 'https://www.youtube.com/live/iMt7azVQsCs',
    'no scheme' => 'youtube.com/watch?v=iMt7azVQsCs',
]);

test('links that are not a single youtube video have no id', function (?string $url): void {
    expect(YouTube::idFromUrl($url))->toBeNull();
})->with([
    'empty' => null,
    'another site' => 'https://vimeo.com/123456789',
    'lookalike domain' => 'https://notyoutube.com/watch?v=iMt7azVQsCs',
    'channel page' => 'https://www.youtube.com/@NASA',
    'playlist' => 'https://www.youtube.com/playlist?list=PL1234567890',
]);
