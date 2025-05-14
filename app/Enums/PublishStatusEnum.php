<?php

namespace App\Enums;

use App\Services\Enums\EnumTrait;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PublishStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case DRAFT = 0;
    case REVIEW = 2;
    case SCHEDULE = 3;
    case PUBLISH = 1;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-o-pencil',
            self::REVIEW => 'heroicon-o-eye',
            self::SCHEDULE => 'heroicon-o-calendar',
            self::PUBLISH => 'heroicon-o-check-circle'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::DRAFT => 'info',
            self::REVIEW => 'warning',
            self::SCHEDULE => 'warning',
            self::PUBLISH => 'success'
        };
    }
}
