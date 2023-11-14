<?php

namespace Barebones\Classes\Twig;

use Barebones\App\Router;
use Exception;
use Twig\Environment;
use Twig\TwigFunction;

class Functions
{
    /** @var Environment */
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->addGetUrlFunction()
            ->addGetLinksFunction()
            ->addGetScriptsFunction();
    }

    protected function addGetUrlFunction()
    {
        $getUrlFunction = new TwigFunction('getUrl', function ($name, $generatorParams = [], $absoluteUrl = false) {
            $router = Router::getInstance();
            return $router->generate($name, $generatorParams, $absoluteUrl);
        });

        $this->twig->addFunction($getUrlFunction);
        return $this;
    }

    protected function addGetScriptsFunction()
    {
        $getScriptsFunction = new TwigFunction('getScripts', function ($entryName, $options = '') {
            return $this->getWebpackFiles($entryName, 'js', $options);
        }, ['is_safe' => ['html']]);

        $this->twig->addFunction($getScriptsFunction);
        return $this;
    }

    protected function addGetLinksFunction()
    {
        $getLinksFunction = new TwigFunction('getLinks', function ($entryName, $options = '') {
            return $this->getWebpackFiles($entryName, 'css', $options);
        }, ['is_safe' => ['html']]);

        $this->twig->addFunction($getLinksFunction);
        return $this;
    }


    protected function getWebpackFiles($entryName, $type, $options = '')
    {
        $result = '';
        try {
            $entrypointFile = json_decode(file_get_contents(APP_ROOT . '/public/build/entrypoints.json'), true);

            if (isset($entrypointFile['entrypoints'][$entryName][$type])) {
                foreach ($entrypointFile['entrypoints'][$entryName][$type] as $filePath) {
                    switch ($type) {
                        case 'css':
                            $result .= "<link rel=\"stylesheet\" href=\"$filePath\" $options>\n";
                            break;
                        case 'js':
                            $result .= "<script src=\"$filePath\" $options></script>\n";
                            break;
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return '';
        }
    }
}
