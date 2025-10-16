<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\PhotographyPage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhotographyPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 *
 * @mixin \Eloquent
 */
class PhotographyPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'photography';

    public $contentAttributes = [
        'flickr_album',
    ];
}
