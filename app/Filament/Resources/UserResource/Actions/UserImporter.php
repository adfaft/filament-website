<?php

namespace App\Filament\Resources\UserResource\Actions;

use App\Enums\ActiveStatusEnum;
use App\Models\User;
use App\Rules\PasswordRule;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->example('John Doe')
                ->requiredMappingForNewRecordsOnly()
                ->ignoreBlankState()
                ->rules(['required']),
            ImportColumn::make('email')
                ->example(['John.Doe@example.com', 'Jane.Dyne@example.net'])
                ->requiredMapping()
                ->rules(['required', 'email']),
            ImportColumn::make('password')
                ->example('ExamplePassword0')
                ->helperText('Password need to be at least 1 uppercase, 1 lowercase, 1 number and a minimum of 8 characaters')
                ->requiredMappingForNewRecordsOnly()
                ->ignoreBlankState()
                ->sensitive()
                ->rules(['required', PasswordRule::default_rule()]),
            ImportColumn::make('roles')
                ->helperText('Use the defined role names found in CMS')
                ->requiredMappingForNewRecordsOnly()
                ->ignoreBlankState()
                ->rules(['required'])
                ->fillRecordUsing(function (User $record, string $state): void {
                    $role = Role::findByName($state);
                    if (! $role) {
                        throw new RowImportFailedException("No User Role has beend defined for {$state} for {$record->email}");
                    }

                    $record->syncRoles($role);
                }),
            ImportColumn::make('status')
                ->examples(['ACTIVE', 'INACTIVE'])
                ->helperText('Only accept "ACTIVE" or "INACTIVE"')
                ->requiredMappingForNewRecordsOnly()
                ->ignoreBlankState()
                ->rules(['required', Rule::in(ActiveStatusEnum::all())])
                ->fillRecordUsing(function (User $record, string $state): void {
                    $status = ActiveStatusEnum::tryGet($state);
                    if (! is_null($status)) {
                        $record->status = $status;
                    }
                }),
        ];

    }

    public function resolveRecord(): ?User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }

    public function getJobQueue(): ?string
    {
        return 'imports';
    }
}
