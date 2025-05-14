# Command

## Filament

Filament User
- `php artisan make:filament-user`
- `php artisan shield:super-admin --user=[id]`

Filament Resources
- `php artisan make:filament-resource Customer`


Filament Setting
- `php artisan make:setting [SettingName] --group=[groupName]` to create Spatie Settings Class
- `php artisan make:settings-migration [CreateSettingMigration]` to create Spatie Settings Class migration
- `php artisan make:filament-settings-page [PageClassNamne] [SettingNameClass]` to create Filament Settings Page based on Spatie 
  
Filament Shield
- `php artisan shield:super-admin --user=[id]` to assign user id as super admin
- `php artisan shield:generate --panel=[panelname]` to generate shield policy for new filament resources
- `php artisan shield:seeder` to generate shield seeder based on current roles
- `php artisan shield:seeder -f` to force generate shield seeder based on current roles

Migration
- `php artisan migrate:fresh --seed && php artisan shield:super-admin --user=1` for fresh install

Laravel Jobs & Queue
- `php artisan schedule:work` to run job schedule worker locally
- `php artisan queue:work` to run job queue worker locally

Production
- `php artisan filament:optimize`
