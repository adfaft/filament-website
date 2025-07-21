<?php

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class AddLastLoginToWebcms
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /**
         * @var User
         */
        $user = $event->user;
        $user->last_login = Carbon::now();
        $user->save();
    }
}
