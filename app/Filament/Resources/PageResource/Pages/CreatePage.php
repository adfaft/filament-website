<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Enums\PostTypeEnum;
use App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['post_type'] = PostTypeEnum::PAGE;

        return $data;
    }
}
