<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Publication;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicationsPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'publications';

    public $contentAttributes = [];

    public function publications()
    {
        return $this->hasMany(Publication::class, 'page_id');
    }
}
