<?php

namespace App\Models;

use App\Enums\LanguageEnum;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasTags;
    use SoftDeletes;

    /**
     * The attributes that are NOT mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    protected $attributes = [
        'status' => 0,
        'lang' => '',
        'translation' => '{}',
        'meta' => '{}',

    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lang' => LanguageEnum::class,
            'translation' => 'json',
            'status' => PublishStatusEnum::class,
            'published_at' => 'datetime',
            'meta' => 'json',
        ];
    }
}
