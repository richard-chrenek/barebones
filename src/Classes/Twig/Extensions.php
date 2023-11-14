<?php

namespace Barebones\Classes\Twig;

use Barebones\App\Config;
use Twig\Environment;
use Twig_Extensions_Extension_I18n;

class Extensions
{
    /** @var Environment */
    protected $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->addI18nExtension();
    }

    protected function addI18nExtension()
    {
        if (Config::LOCALE_ENABLED) {
            $this->twig->addExtension(new Twig_Extensions_Extension_I18n());
        }
    }
}
