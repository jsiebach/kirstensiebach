<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CvPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'cv';

    public $contentAttributes = [
        'cv_file',
    ];
}
