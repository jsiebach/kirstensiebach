<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Press;
use App\Models\SocialLink;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\HomePage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Press> $press
 * @property-read int|null $press_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SocialLink> $socialLinks
 * @property-read int|null $social_links_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 *
 * @mixin \Eloquent
 */
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
