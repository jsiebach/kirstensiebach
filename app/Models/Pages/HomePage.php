<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Press;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomePage extends Page
{
    use HasFactory, HasSlug;

    public $with = ['press', 'socialLinks'];

    public static $slug = 'home';

    public $contentAttributes = [
        'add_call_to_action_banner',
        'call_to_action',
        'action_link',
        'action_text',
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
