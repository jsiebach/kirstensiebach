<?php

namespace App\Rules;

use App\Support\YouTube;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class YouTubeUrl implements ValidationRule
{
    /**
     * Rejects links that aren't to a single YouTube video.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || YouTube::idFromUrl($value) === null) {
            $fail('Please paste a link to a YouTube video, e.g. https://www.youtube.com/watch?v=...');
        }
    }
}
