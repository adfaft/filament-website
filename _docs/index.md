# # Filament Website

Website based on filament
## Requirement

- Laravel 12
- PHP 8.4
- MySQL

## Features

- User Management (features/user_management.md)[features/user_management.md]
- Settings Page (features/settings_management.md)[features/settings_management.md]
- Post & Page Management
  - Include revisions
  - multiple languages
  - publish date
  - review system
- Page Management
  - Include revisions
  - multiple languages
  - publish date
  - review
  - Templating

## Installation

- fill in the environment variables from .env.example, especially for `APP_URL`, all `DB_*` configuration, first super admin `ADMIN_*`
- run `php artisan key:generate` to generate `APP_KEY`
- run `php artisan migrate:fresh --seed` to install based on seeder
- run `php artisan make:filament-user` to create a new user for filament
- run `php artisan shield:super-admin --user=[id]` and assign that user id as the first admin

## Commands

Read Full Documentation Here : (commands.md)[commands.md]

## Advanced Configuration

Read Full Documentation Here : (advanced.md)[advanced.md]

## Library

- Filament Plugins
	- Filament Breezy for Two Factor Authentication
	  [https://filamentphp.com/plugins/jeffgreco-breezy](https://filamentphp.com/plugins/jeffgreco-breezy)
	
	- Filament Shield for Authorization
	  [https://filamentphp.com/plugins/bezhansalleh-shield](https://filamentphp.com/plugins/bezhansalleh-shield)
	
	- Filament Exception Viewer for AdminPanel
	  [https://filamentphp.com/plugins/bezhansalleh-exception-viewer](https://filamentphp.com/plugins/bezhansalleh-exception-viewer)
	
	- Filament Spatie Settings
	  [https://filamentphp.com/plugins/filament-spatie-settings](https://filamentphp.com/plugins/filament-spatie-settings)
	
	- Filament Spatie Tags
	  [https://filamentphp.com/plugins/filament-spatie-tags](https://filamentphp.com/plugins/filament-spatie-tags)
	
	- Filament Spatie Media Library
	  [https://filamentphp.com/plugins/filament-spatie-media-library](https://filamentphp.com/plugins/filament-spatie-media-library)
	
	- Filament Logger
	  [https://filamentphp.com/plugins/z3d0x-logger](https://filamentphp.com/plugins/z3d0x-logger)
	
	- Menu Builder
	  [https://filamentphp.com/plugins/datlechin-menu-builder#model-menu-panel](https://filamentphp.com/plugins/datlechin-menu-builder#model-menu-panel)

- Other
	- Spatie Settings
	  [https://github.com/spatie/laravel-settings](https://github.com/spatie/laravel-settings)
	  
	- Spatie Tags
	  [https://spatie.be/docs/laravel-tags/v4/introduction](https://spatie.be/docs/laravel-tags/v4/introduction)

	- Spatie Translatable
	  [https://spatie.be/docs/laravel-translatable/v6/introduction](https://spatie.be/docs/laravel-translatable/v6/introduction)