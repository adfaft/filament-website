<?php

namespace App\Filament\Components\Forms\Helper;

use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class TitleAndSlug
{
    /**
     * Undocumented function
     *
     * @return array
     */
    public static function make(string $titleInputName, string $slugInputName,
        ?string $prefix = null
    ) {

        $prefix = ! is_null($prefix) ?: \url('/').'/';

        return [

            Forms\Components\TextInput::make($titleInputName)
                ->required()
                ->live(debounce: 600)
                ->afterStateUpdated(function (Set $set, Get $get, $operation, $state) use ($slugInputName) {
                    if (empty($get($slugInputName)) || $operation === 'create') {
                        $set($slugInputName, Str::slug($state));
                    }
                }),
            Forms\Components\TextInput::make($slugInputName)
                ->hiddenLabel(ucfirst($slugInputName))
                ->required()
                ->prefix(\url('/').'/')
                ->extraFieldWrapperAttributes(['class' => '-mt-4'])
                ->extraAttributes(['style' => 'border: none !important; background-color: transparent; box-shadow: none'])
                ->suffixAction(CopyAction::make()),
        ];

    }
}
