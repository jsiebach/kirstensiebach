<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhotographyPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'photography';

    public $contentAttributes = [
        'flickr_album',
    ];
}
