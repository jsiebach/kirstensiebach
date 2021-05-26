<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
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
        static::addGlobalScope('byDate', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }
}
