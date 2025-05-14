<?php

namespace App\Filament\Resources;

use App\Enums\LanguageEnum;
use App\Enums\PublishStatusEnum;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Settings\GeneralSettings;
use App\Support\Facades\Date;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        $timezone = \app(GeneralSettings::class)->timezone_default;

        return $form
            ->schema([

                Grid::make()
                    ->schema([
                        Section::make([
                            Forms\Components\TextInput::make('title')
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->required(),

                            Forms\Components\Textarea::make('excerpt')
                                ->columnSpanFull(),

                            RichEditor::make('content')
                                ->columnSpanFull(),

                        ])
                            ->columnSpan(3),

                        Grid::make()
                            ->schema([

                                Section::make()
                                    ->schema([
                                        Placeholder::make('created_at')->label('Created At:')
                                            ->content(fn (Post $post) => Date::localize($post->created_at))
                                            ->hidden(fn (string $operation) => $operation === 'create'),

                                        Placeholder::make('updated_at')->label('Updated At:')
                                            ->content(fn (Post $post) => Date::localize($post->updated_at))
                                            ->hidden(fn (string $operation) => $operation === 'create'),

                                        DateTimePicker::make('published_at')
                                            ->timezone($timezone)
                                            ->displayFormat('Y-m-d H:i:s')
                                            ->native(false)
                                            ->minDate(now())
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'It will automatically publish if the vaue set to the future and status equals SCHEDULE'),

                                        Select::make('status')
                                            ->options(PublishStatusEnum::class)
                                            ->default(PublishStatusEnum::DRAFT),
                                    ])
                                    ->columns(1),

                                Section::make()
                                    ->label('Translation')
                                    ->schema([
                                        Select::make('lang')
                                            ->label('Language')
                                            ->options(LanguageEnum::class)
                                            ->default(LanguageEnum::ID->value)
                                            ->required(),

                                        TextInput::make('translation'),

                                    ])->columns(1),

                            ])->columnSpan(1),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ]),

                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('SEO')
                            ->schema([
                                TextInput::make('meta.seo.title')
                                    ->label('Title'),

                                Textarea::make('meta.seo.description')
                                    ->label('Description'),

                                TagsInput::make('meta.seo.keywords')
                                    ->label('Keywords'),
                            ]),
                    ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        $timezone = app(GeneralSettings::class)->timezone_default;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(timezone: $timezone, format: 'd F Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(timezone: $timezone, format: 'd F Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->placeholder('-')
                    ->dateTime(timezone: $timezone, format: 'd F Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
