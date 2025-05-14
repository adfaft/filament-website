<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Enums\PostTypeEnum;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['post_type'] = PostTypeEnum::POST;
        $data['translation'] = $data['translation'] ?? [];

        return $data;
    }
}
