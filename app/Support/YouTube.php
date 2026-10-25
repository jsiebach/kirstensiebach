<?php

namespace App\Support;

class YouTube
{
    /**
     * Pull the video id out of any common YouTube link (watch, youtu.be, embed, shorts, live).
     */
    public static function idFromUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $pattern = '~^(?:https?://)?(?:www\.|m\.)?(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})(?:[?&#/].*)?$~';

        return preg_match($pattern, trim($url), $match) ? $match[1] : null;
    }
}
