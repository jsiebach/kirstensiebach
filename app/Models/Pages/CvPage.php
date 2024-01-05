<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\CvPage
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CvPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 * @mixin \Eloquent
 */
class CvPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'cv';

    public $contentAttributes = [
        'cv_file',
    ];
}
