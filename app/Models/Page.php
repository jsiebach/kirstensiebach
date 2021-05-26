<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Page extends Model
{
    use HasFactory;

    public $table = 'pages';

    public $casts = [
        'content' => SchemalessAttributes::class,
    ];

    public $guarded = [
        'id'
    ];

    public $contentAttributes = [];

    public function scopeWithContent(): Builder
    {
        return $this->content->modelCast();
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->contentAttributes)) {
            return $this->content[$key];
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $val)
    {
        if (in_array($key, $this->contentAttributes)) {
            return $this->content->$key = $val;
        }

        parent::setAttribute($key, $val);
    }
}
