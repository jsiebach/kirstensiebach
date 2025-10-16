<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScienceAbstract
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string|null $link
 * @property string $authors
 * @property string $location
 * @property string $city_state
 * @property \Illuminate\Support\Carbon $date
 * @property string $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page|null $page
 *
 * @method static \Database\Factories\ScienceAbstractFactory factory($count = null, $state = [])
 * @method static Builder|ScienceAbstract newModelQuery()
 * @method static Builder|ScienceAbstract newQuery()
 * @method static Builder|ScienceAbstract query()
 * @method static Builder|ScienceAbstract whereAuthors($value)
 * @method static Builder|ScienceAbstract whereCityState($value)
 * @method static Builder|ScienceAbstract whereCreatedAt($value)
 * @method static Builder|ScienceAbstract whereDate($value)
 * @method static Builder|ScienceAbstract whereDetails($value)
 * @method static Builder|ScienceAbstract whereId($value)
 * @method static Builder|ScienceAbstract whereLink($value)
 * @method static Builder|ScienceAbstract whereLocation($value)
 * @method static Builder|ScienceAbstract wherePageId($value)
 * @method static Builder|ScienceAbstract whereTitle($value)
 * @method static Builder|ScienceAbstract whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ScienceAbstract extends Model
{
    use HasFactory;

    public $casts = [
        'date' => 'date',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('byDatePublished', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }
}
