<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use App\Models\Research;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResearchPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'research';

    public $contentAttributes = [
        'banner',
        'intro',
    ];

    public function research()
    {
        return $this->hasMany(Research::class, 'page_id');
    }
}
