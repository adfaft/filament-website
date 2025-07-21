<?php

namespace App\Enums;

use App\Services\Enums\EnumTrait;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LanguageEnum: string implements HasIcon, HasLabel
{
    use EnumTrait;

    case ID = 'id';
    case EN = 'en';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ID => 'icon-flags-id',
            self::EN => 'icon-flags-en',
        };
    }
}
