<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\Publication;
use App\Models\ScienceAbstract;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\PublicationsPage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Publication> $publications
 * @property-read int|null $publications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ScienceAbstract> $scienceAbstracts
 * @property-read int|null $science_abstracts_count
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationsPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 * @mixin \Eloquent
 */
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
