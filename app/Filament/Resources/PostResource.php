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
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\Tag;

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
                    ->columns([
                        'default' => 1,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
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
                                            ->options(function () {
                                                return \collect(PublishStatusEnum::cases())->mapWithKeys(function ($item) {
                                                    return [
                                                        $item->value => view('webcms.components.enum-with-icon', ['item' => $item])->render(),
                                                    ];
                                                })->toArray();
                                            })
                                            ->default(PublishStatusEnum::DRAFT)
                                            ->allowHtml()
                                            ->native(false),
                                    ]),

                                Section::make()
                                    ->schema([
                                        Select::make('lang')
                                            ->label('Language')
                                            ->options(function () {
                                                return \collect(LanguageEnum::cases())->mapWithKeys(function ($item) {
                                                    return [
                                                        $item->value => view('webcms.components.enum-with-icon', ['item' => $item])->render(),
                                                    ];
                                                })->toArray();
                                            })
                                            ->default(LanguageEnum::ID->value)
                                            ->allowHtml()
                                            ->native(false)
                                            ->required(),

                                        TextInput::make('translation'),

                                    ]),

                                Section::make()
                                    ->schema([
                                        SpatieTagsInput::make('category')
                                            ->placeholder('New category')
                                            ->type('category'),

                                        SpatieTagsInput::make('tags')
                                            ->type('tags'),
                                    ]),

                            ])->columnSpan(1),
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
                SpatieTagsColumn::make('category')
                    ->type('category')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(PublishStatusEnum::forSelect()),
                Tables\Filters\SelectFilter::make('lang')
                    ->label('Language')
                    ->options(LanguageEnum::forSelect()),
                Tables\Filters\SelectFilter::make('category')
                    ->options(Tag::where('type', 'category')->get()->pluck('name', 'name'))
                    ->query(fn (Builder $query, array $data) => $query
                        ->when(
                            $data['value'],
                            fn (Builder $query, $value) => $query->withAnyTags([$value], 'category')
                        )
                    ),
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
