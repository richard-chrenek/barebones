<?php

namespace Barebones\Classes\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;
}
