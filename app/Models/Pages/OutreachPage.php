<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Pages\OutreachPage
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
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OutreachPage whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 *
 * @mixin \Eloquent
 */
class OutreachPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'outreach';

    public $contentAttributes = [];
}
