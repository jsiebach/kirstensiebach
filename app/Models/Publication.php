<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Publication
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $publication_name
 * @property string $authors
 * @property int $published
 * @property \Illuminate\Support\Carbon $date_published
 * @property string|null $abstract
 * @property string|null $link
 * @property string|null $doi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page|null $page
 *
 * @method static \Database\Factories\PublicationFactory factory($count = null, $state = [])
 * @method static Builder|Publication newModelQuery()
 * @method static Builder|Publication newQuery()
 * @method static Builder|Publication query()
 * @method static Builder|Publication whereAbstract($value)
 * @method static Builder|Publication whereAuthors($value)
 * @method static Builder|Publication whereCreatedAt($value)
 * @method static Builder|Publication whereDatePublished($value)
 * @method static Builder|Publication whereDoi($value)
 * @method static Builder|Publication whereId($value)
 * @method static Builder|Publication whereLink($value)
 * @method static Builder|Publication wherePageId($value)
 * @method static Builder|Publication wherePublicationName($value)
 * @method static Builder|Publication wherePublished($value)
 * @method static Builder|Publication whereTitle($value)
 * @method static Builder|Publication whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Publication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $casts = [
        'date_published' => 'date',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('byDatePublished', function (Builder $builder) {
            $builder->orderBy('date_published', 'desc');
        });
    }
}
