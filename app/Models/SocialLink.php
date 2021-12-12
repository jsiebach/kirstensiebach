<?php

namespace App\Models;

use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialLink extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    public $sortable = [
        'order_column_name'  => 'sort_order',
        'sort_when_creating' => true,
        'sort_on_has_many'   => true,
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
