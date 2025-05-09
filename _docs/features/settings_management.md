# Settings Management

## Features

## Create New Setting
Ref : (https://github.com/spatie/laravel-settings#usage)[https://github.com/spatie/laravel-settings#usage]

- `php artisan make:setting [SettingName] --group=[groupName]` to create a new setting class under `app/Settings` directory and the settings value as **class properties**
```php
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    
    public bool $site_active;

    public ?string $email;
    
    public static function group(): string
    {
        return 'general';
    }
    public static function encrypted(): array
    {
        return [
            'email'
        ];
    }
}
```
- enable settings in `config/settings.php`
```php
/*
* Each settings class used in your application must be registered, you can
* add them (manually) here.
*/
'settings' => [
    GeneralSettings::class
],
```
- `php artisan make:settings-migration [CreateSettingMigration]` to create migration for default value 
```php
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // settings db [grup].[setting_name_in_properties]
        $this->migrator->add('general.site_name', 'Spatie');
        $this->migrator->add('general.site_active', true);
        $this->migrator->encrypt('general.email', 'john.doe@example.com');

        // Or, group based on group name
        $this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('site_name', 'Spatie');
            $blueprint->add('site_active', true);
            $blueprint->encrypt('general.email', 'john.doe@example.com');
        });
    }
}
```
- run `php artisan migrate`
- create Filament Settings Page `php artisan make:filament-settings-page [PageClassNamne] [SettingNameClass]`
- build form using filament form builder
```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
 
public function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('copyright')
                ->label('Copyright notice')
                ->required(),
            Repeater::make('links')
                ->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('url')
                        ->url()
                        ->required(),
                ]),
        ]);
}
```
