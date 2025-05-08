<?php

namespace App\Filament\Resources\UserResource\Actions;

use App\Models\User;
use App\Support\Facades\Date;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('roles')
                ->state(fn (User $user) => $user->getRoleNames()->first()),
            ExportColumn::make('status')
                ->formatStateUsing(fn ($state) => $state->name),
            ExportColumn::make('last_login')
                ->label('Last Login')
                ->formatStateUsing(fn ($state) => ! $state ? '' : Date::localize($state))
                ->enabledByDefault(false),
            ExportColumn::make('is_otp_enabled')
                ->label('Is OTP Enabled?')
                ->formatStateUsing(fn (string $state): string => $state ? 'yes' : 'no')
                ->enabledByDefault(false),

        ];

    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }

    public function getJobQueue(): ?string
    {
        return 'exports';
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
        ];
    }
}
