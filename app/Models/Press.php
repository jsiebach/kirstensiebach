<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Press
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $link
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page|null $page
 * @method static \Database\Factories\PressFactory factory($count = null, $state = [])
 * @method static Builder|Press newModelQuery()
 * @method static Builder|Press newQuery()
 * @method static Builder|Press query()
 * @method static Builder|Press whereCreatedAt($value)
 * @method static Builder|Press whereDate($value)
 * @method static Builder|Press whereId($value)
 * @method static Builder|Press whereLink($value)
 * @method static Builder|Press wherePageId($value)
 * @method static Builder|Press whereTitle($value)
 * @method static Builder|Press whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Press extends Model
{
    use HasFactory;

    public $table = 'press';

    public $casts = [
        'date' => 'date',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('byDate', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }
}
