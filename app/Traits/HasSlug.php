<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait HasSlug
{
    protected static function booted()
    {
        static::addGlobalScope('pageScope', function (Builder $builder) {
            $builder->where('slug', '=', self::$slug);
        });
    }
}
