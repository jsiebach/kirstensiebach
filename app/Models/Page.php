<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

/**
 * App\Models\Page
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
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page whereContent($value)
 * @method static Builder|Page whereCreatedAt($value)
 * @method static Builder|Page whereId($value)
 * @method static Builder|Page whereMetaDescription($value)
 * @method static Builder|Page whereMetaTitle($value)
 * @method static Builder|Page whereSlug($value)
 * @method static Builder|Page whereTitle($value)
 * @method static Builder|Page whereUpdatedAt($value)
 * @method static Builder|Page withContent()
 *
 * @mixin \Eloquent
 */
class Page extends Model implements Sitemapable
{
    use HasFactory;

    public $table = 'pages';

    public $casts = [
        'content' => SchemalessAttributes::class,
    ];

    public $guarded = [
        'id',
    ];

    public $contentAttributes = [];

    public function scopeWithContent(): Builder
    {
        return $this->content->modelCast();
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->contentAttributes)) {
            return $this->content[$key];
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->contentAttributes)) {
            return $this->content->$key = $value;
        }

        parent::setAttribute($key, $value);
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('page.show', $this))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.1);
    }
}
