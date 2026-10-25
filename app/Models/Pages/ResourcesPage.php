<?php

namespace App\Models\Pages;

use App\Models\Page;
use App\Models\ResourceItem;
use App\Models\ResourceSection;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;

/**
 * Curriculum and outreach material Kirsten points people to.
 *
 * Stays out of the menu and the sitemap until `show_in_menu` is switched on in the admin.
 *
 * @property string|null $intro
 * @property bool|null $show_in_menu
 */
class ResourcesPage extends Page
{
    use HasFactory, HasSlug;

    public $with = [];

    public static $slug = 'resources';

    public $contentAttributes = [
        'intro',
        'show_in_menu',
    ];

    public function isListed(): bool
    {
        return (bool) $this->show_in_menu;
    }

    public static function isListedInMenu(): bool
    {
        return (bool) static::first()?->isListed();
    }

    /**
     * Resources that aren't in a section, shown above the first section.
     *
     * @return Collection<int, ResourceItem>
     */
    public function unsectionedResourceItems(): Collection
    {
        return ResourceItem::whereNull('resource_section_id')->ordered()->get();
    }

    /**
     * Sections in their admin order with their resources, leaving out empty ones.
     *
     * @return Collection<int, ResourceSection>
     */
    public function resourceSections(): Collection
    {
        return ResourceSection::ordered()
            ->with('resourceItems')
            ->get()
            ->filter(fn (ResourceSection $section): bool => $section->resourceItems->isNotEmpty())
            ->values();
    }
}
