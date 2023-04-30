<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Publication;
use App\Models\ScienceAbstract;
use App\Traits\HasSlug;
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

    public function scienceAbstracts()
    {
        return $this->hasMany(ScienceAbstract::class, 'page_id');
    }
}
