<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'lab';

    public $contentAttributes = [
        'banner',
        'intro',
        'lower_content',
    ];

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'page_id');
    }
}
