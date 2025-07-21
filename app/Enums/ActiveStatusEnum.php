<?php

namespace App\Enums;

use App\Services\Enums\EnumTrait;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ActiveStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::INACTIVE => 'heroicon-o-x-circle',
            self::ACTIVE => 'heroicon-o-check-circle'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::INACTIVE => 'danger',
            self::ACTIVE => 'success'
        };
    }
}
