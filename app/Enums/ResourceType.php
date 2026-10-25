<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ResourceType: string implements HasIcon, HasLabel
{
    case Document = 'document';
    case Image = 'image';
    case Video = 'video';
    case Link = 'link';

    public function getLabel(): string
    {
        return $this->name;
    }

    /**
     * Heroicon used in the admin.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Document => 'heroicon-o-document-text',
            self::Image => 'heroicon-o-photo',
            self::Video => 'heroicon-o-play-circle',
            self::Link => 'heroicon-o-link',
        };
    }

    /**
     * Whether this type is an uploaded file rather than a link.
     */
    public function hasFile(): bool
    {
        return $this === self::Document || $this === self::Image;
    }

    /**
     * Font Awesome 4 icon used on the public site.
     */
    public function siteIcon(): string
    {
        return match ($this) {
            self::Document => 'fa-file-text-o',
            self::Image => 'fa-picture-o',
            self::Video => 'fa-youtube-play',
            self::Link => 'fa-external-link',
        };
    }
}
