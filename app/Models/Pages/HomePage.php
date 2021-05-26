<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Press;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Page
{
    use HasFactory, HasSlug;

    public $with = ['press', 'socialLinks'];

    public static $slug = 'home';

    public $contentAttributes = [
        'use_callout',
        'callout',
        'callout_action',
        'callout_action_text',
        'banner',
        'tagline',
        'profile_picture',
        'profile_summary',
        'bio',
    ];

    public function press()
    {
        return $this->hasMany(Press::class, 'page_id');
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class, 'page_id');
    }
}
