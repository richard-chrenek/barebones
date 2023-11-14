<?php

namespace Barebones\Api;

use Barebones\App\View;
use Barebones\Controller\BaseController;
use Barebones\Service\PersonService;

/**
 * Controller demonstrating basic JSON and XML API responses, along with different controller namespace
 * resolving in the Router class
 */
class ApiController extends BaseController
{
    public function jsonDemo()
    {
        return View::json([
            'text' => 'Lorem ipsum dolor sit amet',
            'number' => 42,
            'class' => PersonService::getInstance()->read(1)
        ]);
    }

    public function xmlDemo()
    {
        return View::xml([
            'text' => 'Lorem ipsum dolor sit amet',
            'number' => 42,
            'outer' => ['inner' => 'someValue'],
        ], 'data');
    }
}
