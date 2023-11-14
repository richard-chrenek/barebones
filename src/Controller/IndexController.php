<?php

namespace Barebones\Controller;

use Barebones\App\View;
use Barebones\Classes\Exceptions\MethodNotAllowedException;
use Barebones\Classes\Exceptions\MishandledInputException;
use Barebones\Classes\Exceptions\NotAllowedException;
use Barebones\Classes\Exceptions\NotFoundException;
use Exception;

class IndexController extends BaseController
{
    public function index()
    {
        View::render('pages/index.html.twig');
    }

    /**
     * Demonstrates app error handling functionality. If any of this exceptions will be encountered during the app
     * runtime, it will be automatically delegated and handled by ErrorController.
     * @param int $statusCode
     * @throws Exception
     * @see ErrorController
     * @see App::run()
     */
    public function exceptionDemo($statusCode)
    {
        $message = $this->getQueryParameter('message', 'Test exception');

        switch ($statusCode) {
            case 403:
                throw new NotAllowedException($message);
            case 404:
                throw new NotFoundException($message);
            case 405:
                throw new MethodNotAllowedException($message);
            case 422:
                throw new MishandledInputException($message);
            case 500:
            default:
                throw new Exception($message);
        }
    }
}
