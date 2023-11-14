<?php

namespace Barebones\Controller;

use Barebones\App\Config;
use Barebones\App\View;
use Exception;

class ErrorController extends BaseController
{
    /**
     * Error constructor.
     * @param Exception $errorInstance
     */
    public function __construct(Exception $errorInstance)
    {
        parent::__construct();
        switch ($errorInstance->getCode()) {
            case 403:
                $this->notAllowedException($errorInstance);
                break;
            case 404:
                $this->notFoundException($errorInstance);
                break;
            case 405:
                $this->methodNotAllowedException($errorInstance);
                break;
            case 422:
                $this->unprocessableEntityException($errorInstance);
                break;
            case 500:
            default:
                $this->internalServerErrorException($errorInstance);
                break;
        }
    }

    protected function notAllowedException(Exception $errorInstance)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Not Allowed', true, 403);

        $this->render(
            $errorInstance,
            _('You shall NOT pass!'),
            _('Access to this page is forbidden (either for you, or for everybody).')
        );
    }

    protected function notFoundException(Exception $errorInstance)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);

        $this->render(
            $errorInstance,
            _('Darn, where did we put that page?'),
            _('This page is nowhere to be found.')
        );
    }

    protected function unprocessableEntityException(Exception $errorInstance)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 422 Unprocessable Entity', true, 422);

        $this->render(
            $errorInstance,
            _('Look, I just... can\'t'),
            _('Please repeat the request with the correct parameters.')
        );
    }

    protected function methodNotAllowedException(Exception $errorInstance)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed', true, 405);

        $this->render(
            $errorInstance,
            _('Wrong way!'),
            _('This might definitely work, but not with this method.')
        );
    }

    protected function internalServerErrorException(Exception $errorInstance)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

        $this->render(
            $errorInstance,
            _('Unexpected error!'),
            _('This looks really bad. We are trying to fix everything up.')
        );
    }

    public function render(
        Exception $errorInstance,
        $heading = 'Ooops, an error occured',
        $description = 'We will do everything to fix it ASAP.'
    ) {
        View::render(
            'layouts/error.html.twig',
            [
                'error' => (Config::DEBUG === true ? $errorInstance : null),
                'heading' => $heading,
                'description' => $description,
            ]
        );
    }
}
