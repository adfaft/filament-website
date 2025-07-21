<?php

namespace App\Models;

use App\Enums\LanguageEnum;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;

    /**
     * The attributes that are NOT mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lang' => LanguageEnum::class,
            'status' => PublishStatusEnum::class,
            'published_at' => 'datetime',
            'meta' => 'json',
        ];
    }
}
