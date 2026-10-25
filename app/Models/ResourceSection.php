<?php

namespace App\Models;

use Database\Factories\ResourceSectionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * A named heading on the Resources page that groups resources.
 *
 * @property int $id
 * @property int $sort_order
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ResourceItem> $resourceItems
 */
class ResourceSection extends Model implements Sortable
{
    /** @use HasFactory<ResourceSectionFactory> */
    use HasFactory, SortableTrait;

    protected $fillable = [
        'sort_order',
        'name',
        'description',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    /**
     * @return HasMany<ResourceItem, $this>
     */
    public function resourceItems(): HasMany
    {
        return $this->hasMany(ResourceItem::class)->ordered();
    }
}
