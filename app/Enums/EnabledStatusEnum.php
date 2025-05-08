<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EnabledStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    case ENABLED = 1;
    case DISABLED = 0;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ENABLED => 'heroicon-o-check-circle',
            self::DISABLED => 'heroicon-o-x-circle'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ENABLED => 'success',
            self::DISABLED => 'danger'
        };
    }
}
