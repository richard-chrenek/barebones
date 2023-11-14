<?php

namespace Barebones\Classes\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    protected $code = 405;
}
