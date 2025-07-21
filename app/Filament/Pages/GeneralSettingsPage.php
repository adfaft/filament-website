<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Facades\File;

class GeneralSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $navigationGroup = 'setting';

    protected static ?string $navigationLabel = 'General';

    protected static ?int $navigationSort = 10;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('site_name')
                    ->label('Site Name')
                    ->required(),

                Select::make('timezone_default')
                    ->label('Timezone Default')
                    ->helperText('for local datetime display in browser')
                    ->options(File::json(resource_path('data/timezone.json')))
                    ->searchable()
                    ->required(),
            ]);
    }
}
