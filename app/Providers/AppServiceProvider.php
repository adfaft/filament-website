<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use DateTimeZone;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // set default timezone globally
        \App\Support\Date::set_timezone_default(new DateTimeZone(\app(GeneralSettings::class)->timezone_default));

        // morph database
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            'post' => \App\Models\Post::class,
        ]);
    }
}
