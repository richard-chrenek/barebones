<?php

namespace Barebones\Classes\Twig;

use Barebones\Helpers\ExceptionHelper;
use Twig\Environment;

class Globals
{
    /** @var Environment */
    protected $twig;

    public function __construct(Environment $twig)
    {
        foreach ($this->getGlobals() as $name => $value) {
            $twig->addGlobal($name, $value);
        }
    }

    /**
     * Method returning globals in ([key: string]: mixed) format, accessible in all Twig templates.
     * @note Attempt to refactor this method into constant (f. e. in Config) will result into error
     * "Expression is not allowed as class constant value", whenever values like $_SESSION are provided.
     * @return array[]
     */
    protected function getGlobals()
    {
        return [
            'session' => $_SESSION,
            'ExceptionHelper' => new ExceptionHelper(),
        ];
    }
}
