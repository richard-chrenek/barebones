<?php

namespace Barebones\Constants;

class InputSanitizerConstant
{
    const BOOL = 0;
    const INT = 1;
    const UNSIGNED_INT = 2;
    const FLOAT = 3;
    const STRING = 4;

    /**
     * Checks, whether passed value is present within the constants
     * @param string $value
     * @return bool
     */
    public static function isValid($value)
    {
        switch ($value) {
            case self::BOOL:
            case self::INT:
            case self::UNSIGNED_INT:
            case self::FLOAT:
            case self::STRING:
                return true;
        }

        return false;
    }
}
