<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
