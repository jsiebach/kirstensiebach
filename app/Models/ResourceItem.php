<?php

namespace App\Models;

use App\Enums\ResourceType;
use App\Support\YouTube;
use Database\Factories\ResourceItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * A downloadable file, picture, YouTube video or link listed on the Resources page.
 *
 * @property int $id
 * @property int $sort_order
 * @property string $title
 * @property string|null $description
 * @property ResourceType $type
 * @property int|null $resource_section_id
 * @property string|null $file_path
 * @property string|null $file_name
 * @property string|null $url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ResourceSection|null $section
 */
class ResourceItem extends Model implements Sortable
{
    /** @use HasFactory<ResourceItemFactory> */
    use HasFactory, SortableTrait;

    protected $fillable = [
        'sort_order',
        'title',
        'description',
        'type',
        'resource_section_id',
        'file_path',
        'file_name',
        'url',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    /**
     * Keep only the fields the type uses, and remove uploads nothing points to any more
     * so replaced and deleted files don't pile up on the server's disk.
     */
    protected static function booted(): void
    {
        static::saving(function (ResourceItem $item): void {
            if ($item->type->hasFile()) {
                $item->url = null;
            } else {
                $item->file_path = null;
                $item->file_name = null;
            }
        });

        static::saved(function (ResourceItem $item): void {
            $previousFile = $item->getOriginal('file_path');

            if ($item->wasChanged('file_path') && $previousFile) {
                Storage::disk('public')->delete($previousFile);
            }
        });

        static::deleted(function (ResourceItem $item): void {
            if ($item->file_path) {
                Storage::disk('public')->delete($item->file_path);
            }
        });
    }

    /**
     * @return BelongsTo<ResourceSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(ResourceSection::class, 'resource_section_id');
    }

    protected function casts(): array
    {
        return [
            'type' => ResourceType::class,
        ];
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? '/storage/'.$this->file_path : null;
    }

    /**
     * Name the browser saves a download as.
     */
    public function downloadName(): ?string
    {
        return $this->file_name ?: ($this->file_path ? basename($this->file_path) : null);
    }

    /**
     * File type and size shown on the download button, e.g. "PDF, 2.4 MB".
     */
    public function fileDetails(): ?string
    {
        if (! $this->file_path || ! Storage::disk('public')->exists($this->file_path)) {
            return null;
        }

        $extension = strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION));
        $size = Storage::disk('public')->size($this->file_path);
        $readableSize = $size >= 1048576 ? round($size / 1048576, 1).' MB' : max(1, round($size / 1024)).' KB';

        return trim($extension.', '.$readableSize, ', ');
    }

    public function youtubeId(): ?string
    {
        return YouTube::idFromUrl($this->url);
    }
}
