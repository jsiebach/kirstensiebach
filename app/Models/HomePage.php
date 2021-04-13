<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Page
{
    use HasFactory;

    public $with = ['socialLinks'];

    public static $slug = 'home';

    public $contentAttributes = [
        'banner',
        'tagline',
        'profile_picture',
        'profile_summary',
        'bio'
    ];

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class, 'page_id');
    }
}
