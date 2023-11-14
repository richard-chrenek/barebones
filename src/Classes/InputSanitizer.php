<?php

namespace Barebones\Classes;

use Barebones\Classes\Exceptions\MishandledInputException;
use Barebones\Constants\InputSanitizerConstant;

class InputSanitizer
{

    /**
     * Validates and sanitizes user-submitted values
     * @param string $input
     * @param InputSanitizerConstant $type
     * @return mixed
     * @throws MishandledInputException
     */
    public static function sanitize($input, $type)
    {
        $filteredInput = null;

        switch ($type) {
            case InputSanitizerConstant::BOOL:
                $filteredInput = filter_var($input, FILTER_VALIDATE_BOOLEAN);
                break;
            case InputSanitizerConstant::INT:
                $filteredInput = filter_var($input, FILTER_VALIDATE_INT);
                break;
            case InputSanitizerConstant::UNSIGNED_INT:
                $filteredInput = filter_var($input, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
                break;
            case InputSanitizerConstant::FLOAT:
                $filteredInput = filter_var($input, FILTER_VALIDATE_FLOAT);
                break;
            case InputSanitizerConstant::STRING:
                $filteredInput = filter_var($input, FILTER_SANITIZE_STRING);
                break;
        }

        if (empty($filteredInput) && $type !== InputSanitizerConstant::BOOL) {
            throw new MishandledInputException("Mishandled input!");
        }

        return $filteredInput;
    }
}
