<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Research;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\ResearchPage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Research> $research
 * @property-read int|null $research_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResearchPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 *
 * @mixin \Eloquent
 */
class ResearchPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'research';

    public $contentAttributes = [
        'banner',
        'intro',
    ];

    public function research()
    {
        return $this->hasMany(Research::class, 'page_id');
    }
}
