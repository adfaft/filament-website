<?php

namespace App\Services\Password;

use Illuminate\Validation\Rules\Password;

class PasswordRule
{
    public static function default_rule()
    {
        return Password::default()
            ->min(8)
            ->mixedCase()
            ->numbers();
        // ->uncompromised(3)
    }
}
