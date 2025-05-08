<?php

namespace App\Filament\Resources;

use App\Enums\ActiveStatusEnum;
use App\Enums\EnabledStatusEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\Facades\Date;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Split::make([
                    Section::make([
                        TextInput::make('name'),
                        TextInput::make('email'),

                        Section::make([
                            TextInput::make('password')->password(),
                            TextInput::make('password_confirmation')->password()
                                ->label('Password Confirmation'),
                        ])
                            ->compact()
                            ->hidden(fn (string $operation) => $operation === 'view'),
                    ]),

                    Section::make([
                        Placeholder::make('created_at')->label('Created At:')
                            ->content(fn (User $user) => Date::localize($user->created_at))
                            ->hidden(fn (string $operation) => $operation === 'create'),

                        Placeholder::make('updated_at')->label('Updated At:')
                            ->content(fn (User $user) => Date::localize($user->updated_at))
                            ->hidden(fn (string $operation) => $operation === 'create'),

                        Placeholder::make('last_login')->label('Last Login:')
                            ->content(fn (User $user) => Date::localize($user->last_login))
                            ->hidden(fn (string $operation) => $operation === 'create'),

                        Toggle::make('totp_enabled')
                            ->label('TOTP Enabled?')
                            ->disabled()
                            ->formatStateUsing(function (User $user) {
                                return $user->breezy_session?->two_factor_confirmed_at;
                            })
                            ->hidden(fn (string $operation) => $operation !== 'view'),

                        Select::make('user_role')
                            ->relationship(name: 'roles', titleAttribute: 'name'),

                        ToggleButtons::make('status')
                            ->options(ActiveStatusEnum::class)
                            ->default(ActiveStatusEnum::INACTIVE)
                            ->grouped(),

                    ])->grow(false),

                ])
                    ->from('md')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('user_role')
                    ->state(function (User $record): string {
                        $value = $record->roles()->get(['name'])->pluck('name')->implode(', ');

                        return Str::of($value)->replace('_', ' ')->title();
                    }),
                TextColumn::make('totp_enabled')
                    ->badge()
                    ->toggleable()
                    ->label('TOTP?')
                    ->state(fn (User $user): string => EnabledStatusEnum::from((int) $user->is_otp_enabled)->getLabel())
                    ->icon(fn (User $user): string => EnabledStatusEnum::from((int) $user->is_otp_enabled)->getIcon())
                    ->color(fn (User $user) => EnabledStatusEnum::from((int) $user->is_otp_enabled)->getColor()),

                TextColumn::make('last_login')
                    ->formatStateUsing(fn ($state) => Date::localize($state)),
                TextColumn::make('created_at')
                    ->formatStateUsing(fn ($state) => Date::localize($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->formatStateUsing(fn ($state) => Date::localize($state))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
