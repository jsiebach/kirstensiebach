<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * App\Models\TeamMember
 *
 * @property int $id
 * @property int $page_id
 * @property int $sort_order
 * @property string $name
 * @property string|null $title
 * @property string $email
 * @property int|null $alumni
 * @property string $bio
 * @property string $profile_picture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page|null $page
 * @method static \Database\Factories\TeamMemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereAlumni($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TeamMember whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TeamMember extends Model implements Sortable
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
