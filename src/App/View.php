<?php

namespace Barebones\App;

use Barebones\Classes\Singleton;
use Barebones\Classes\Twig\Extensions;
use Barebones\Classes\Twig\Filters;
use Barebones\Classes\Twig\Functions;
use Barebones\Classes\Twig\Globals;
use Exception;
use SimpleXMLElement;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;

class View extends Singleton
{
    /** @var Environment */
    protected $twig;

    protected function __construct()
    {
        parent::__construct();

        if (!is_dir(Config::TWIG_VIEWS_ROOT)) {
            mkdir(Config::TWIG_VIEWS_ROOT);
        }
        $loader = new FilesystemLoader(Config::TWIG_VIEWS_ROOT);

        if (Config::TWIG_CACHE_DIR !== false && !is_dir(Config::TWIG_CACHE_DIR)) {
            mkdir(Config::TWIG_CACHE_DIR);
        }
        $this->twig = new Environment($loader, [
            'debug' => Config::DEBUG,
            'strict_variables' => true,
            'cache' => Config::TWIG_CACHE_DIR,
        ]);

        // Register Twig globals
        new Globals($this->twig);
        // Register additional functions
        new Functions($this->twig);
        // Register additional filters
        new Filters($this->twig);
        // Register additional extensions
        new Extensions($this->twig);
    }

    /**
     * Renders Twig template with provided variables
     * @param string $template Path (from root folder specified in config) with name and extension of Twig file,
     * which should be rendered
     * @param array $context An array of parameters to pass to the template
     * @param bool $noEcho If true, template output will not be echoed. Useful, when you want to pass the template into
     * another variable and not to display it to user.
     * @return string Template output or empty string (when $noEcho is set to true), to comply with return type
     * @see Config::TWIG_VIEWS_ROOT
     */
    public static function render($template, $context = [], $noEcho = false)
    {
        $instance = self::getInstance();

        try {
            $template = $instance->twig->render($template, $context);
        } catch (Error $e) {
            Logger::getInstance()->error($e->getMessage(), $e->getTraceAsString());
        }

        if ($noEcho) {
            return $template;
        }

        echo $template;
        return '';
    }

    public static function json($data = '{}', $encoding = 'utf-8', $noEcho = false)
    {
        header("Content-Type: application/json; charset=$encoding");

        try {
            $output = json_encode($data);

            if ($output === false) {
                throw new Exception(json_last_error_msg());
            }
        } catch (Exception $e) {
            $output['error'] = [
                'code' => $e->getCode(),
                'message' => _('Sorry, something wrong has happened!'),
            ];

            if (Config::DEBUG) {
                $output['errorMessage'] = $e->getMessage();
                $output['trace'] = $e->getTrace();
            }
        }

        if ($noEcho) {
            return $output;
        }

        echo $output;
        return '';
    }
    public static function xml($data = [], $rootElement = 'root', $encoding = 'utf-8', $noEcho = false)
    {
        header("Content-Type: text/xml; charset=$encoding");

        try {
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="' . $encoding . '"?><'.$rootElement.'/>');
            $xml = self::array2XML($xml, $data)->asXML();

            if ($xml === false) {
                throw new Exception('Error creating XML!');
            }
        } catch (Exception $e) {
            $xml = '<?xml version="1.0" encoding="'. $encoding .'"?>
                        <error>
                            <code>' . $e->getCode() . '</code>
                            <message>Sorry, something wrong has happened!</message>';

            if (Config::DEBUG) {
                $xml .= '<errorMessage>' . $e->getMessage() . '</errorMessage>
                            <trace>' . $e->getTraceAsString() . '</trace>';
            }

            $xml .= '</error>';
        }

        if ($noEcho) {
            return $xml;
        }

        echo $xml;
        return '';
    }

    protected static function array2XML($obj, $array)
    {
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }

            if (is_array($value)) {
                $node = $obj->addChild($key);
                self::array2XML($node, $value);
            } else {
                $obj->addChild($key, htmlspecialchars($value));
            }
        }

        return $obj;
    }
}
