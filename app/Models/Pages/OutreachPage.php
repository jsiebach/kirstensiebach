<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutreachPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'outreach';

    public $contentAttributes = [];
}
