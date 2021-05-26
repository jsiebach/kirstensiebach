<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'lab';

    public $contentAttributes = [
        'banner',
        'intro',
        'lower_content'
    ];

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'page_id');
    }
}
