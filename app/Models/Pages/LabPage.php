<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\TeamMember;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\LabPage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TeamMember> $teamMembers
 * @property-read int|null $team_members_count
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LabPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 * @mixin \Eloquent
 */
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
