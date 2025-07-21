<?php

namespace App\Enums;

use App\Services\Enums\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum PostTypeEnum implements HasLabel
{
    use EnumTrait;

    case PAGE;
    case POST;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
