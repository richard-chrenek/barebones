<?php
/**
 * Class MishandledInputException
 * @package Barebones\Constants
 * @author Richard Chrenek <richard.chrenek@ringier.sk>
 * @copyright (c) Ringier Axel Springer Slovakia a.s.
 * @filesource MishandledInputException.php
 */

namespace Barebones\Classes\Exceptions;

class MishandledInputException extends \Exception
{
    protected $code = 422;
}
