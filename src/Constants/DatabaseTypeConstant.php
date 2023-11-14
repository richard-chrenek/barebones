<?php

namespace Barebones\Constants;

class DatabaseTypeConstant
{
    const NONE = '';
    const MYSQL = 'mysql';
    const POSTGRESQL = 'pgsql';
    const SQLITE = 'sqlite';

    /**
     * Checks, whether passed value is present within the constants
     * @param string $value
     * @return bool
     */
    public static function isValid($value)
    {
        switch ($value) {
            case self::NONE:
            case self::MYSQL:
            case self::POSTGRESQL:
            case self::SQLITE:
                return true;
        }

        return false;
    }
}
