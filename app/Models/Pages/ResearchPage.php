<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Research;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'research';

    public $contentAttributes = [
        'banner',
        'intro'
    ];

    public function research()
    {
        return $this->hasMany(Research::class, 'page_id');
    }
}
