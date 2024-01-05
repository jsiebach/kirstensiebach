<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * App\Models\SocialLink
 *
 * @property int $id
 * @property int $page_id
 * @property int $sort_order
 * @property string|null $title
 * @property string $link
 * @property string $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page|null $page
 * @method static \Database\Factories\SocialLinkFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialLink whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SocialLink extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
