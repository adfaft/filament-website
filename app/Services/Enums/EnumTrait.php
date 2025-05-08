<?php

namespace App\Services\Enums;

use ValueError;

/**
 * General Purpose Trait for BackendEnum
 *
 * ref: https://laracasts.com/discuss/channels/laravel/enum-validation-rules-only-works-by-backedenum-interface
 */
trait EnumTrait
{
    public static function all()
    {
        return array_map(function ($c) {
            return $c->name;
        }, self::cases());
    }

    public static function get(string $name)
    {
        if (defined("self::$name")) {
            return constant("self::$name");
        }

        throw new ValueError("{$name} is not a valid case in enum '".get_called_class()."'");
    }

    public static function tryGet(string $name)
    {
        return defined("self::$name") ? constant("self::$name") : null;
    }
}
