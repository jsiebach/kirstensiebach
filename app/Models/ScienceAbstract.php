<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
