<?php

namespace Barebones\App;

use Barebones\Classes\Exceptions\MethodNotAllowedException;
use Barebones\Classes\Exceptions\MishandledInputException;
use Barebones\Classes\Exceptions\NotFoundException;
use Barebones\Classes\InputSanitizer;
use Barebones\Classes\Singleton;

/** Used for matching URL query with proper controller and action */
class Router extends Singleton
{
    /** @var string Namespace for controllers */
    const DEFAULT_NAMESPACE = '\\Barebones\\Controller\\';

    /** @var array Matched route array from config */
    private $route;

    /** @var string Controller name, with default value for fallback case */
    private $controller = self::DEFAULT_NAMESPACE . 'IndexController';

    /** @var string Controller method name, with default value for fallback case */
    private $action = 'index';

    /** @var array Route parameters passed to called action in matched controller */
    private $parameters = [];

    /** @var array Query parameters passed to called action in matched controller */
    private $query = [];

    /**
     * Router constructor. Attempts to load routes from ENV specific config file for later usage. However, if only
     * one global file in the root of config folder is present, it will be used across all environments.
     */
    protected function __construct()
    {
        parent::__construct();

        // Attempt to load routes file for the current ENV
        $routesEnvPath = APP_ROOT . '/config/' . APP_ENV . '/routes.php';

        // However, if only one global file in the root of config folder is used across all environments, load it.
        if (!file_exists($routesEnvPath)) {
            $routesEnvPath = APP_ROOT . '/config/routes.php';
        }

        // Require the file with routes (throws an error, if not found)
        require_once $routesEnvPath;
    }

    /** Tests URL query (with GET parameters stripped) for match.
     * If matched, calls methods for checking and setting the controller, action and parameters.
     * @throws NotFoundException If no match is found, and fallback to homepage is not defined or set to false,
     * or request method is not within allowed route methods.
     * @throws MethodNotAllowedException If match is found, but called with method not present in route whitelist.
     * @throws MishandledInputException If parameter sanitization fails.
     */
    public function match()
    {
        $url = $_SERVER['REQUEST_URI'];
        if ($this->matchRoute($url) === false) {
            if (defined(Config::class .'::ROUTER_FALLBACK') && !empty(Config::ROUTER_FALLBACK)) {
                header('Location: ' . Config::ROUTER_FALLBACK);
                return;
            }
            throw new NotFoundException("URL " . $url . " was not matched with any route!");
        }

        if (!empty($this->route["methods"]) && !in_array($_SERVER['REQUEST_METHOD'], $this->route["methods"])) {
            throw new MethodNotAllowedException("Calling method is not allowed for this route!");
        }

        $namespace = array_key_exists('namespace', $this->route) ? $this->route['namespace'] : self::DEFAULT_NAMESPACE;
        $this->setController($this->route['controller'], $namespace);
        $this->setAction($this->route['action']);

        if (empty($this->parameters)) {
            $this->setParams();
        }

        if (empty($this->query)) {
            $this->setQueryParams();
        }
    }

    /**
     * Parses controller name and checks, if it exists. If yes, sets parsed name as class $controller field.
     * @param string $className Short-form of controller class name
     * @param string $namespace Default or custom namespace value.
     * @throws NotFoundException If class with parsed name does not exists in controller namespace.
     */
    private function setController($className, $namespace = self::DEFAULT_NAMESPACE)
    {
        $fullClassName = $namespace . ucfirst($className) . 'Controller';
        if (class_exists($fullClassName)) {
            $this->controller = $fullClassName;
        } else {
            throw new NotFoundException("Controller " . ucfirst($className) . " was not found!");
        }
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Checks if controller contains method with given name. If yes, sets it as class $action field.
     * @param string $methodName Name of controller function to be checked
     * @throws NotFoundException If controller does not contain given method
     */
    private function setAction($methodName)
    {
        if (method_exists($this->controller, $methodName)) {
            $this->action = $methodName;
        } else {
            throw new NotFoundException("Action " . $methodName . " was not found in class " . $this->controller . "!");
        }
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Checks, if matched route contains parameters defined in route's parameters config option.
     * If yes, sets them as class $parameters field.
     * If matched route is a regular expression, parameters are sanitized and added in order,
     * specified in routes config file.
     * @param array $matches
     * @throws MishandledInputException If value for specified parameter was not provided or coudln't be sanitized
     * properly.
     */
    private function setParams($matches = [])
    {
        if (!empty($this->route['parameters'])) {
            foreach ($this->route['parameters'] as $paramName => $paramType) {
                $this->parameters[] = InputSanitizer::sanitize($matches[$paramName], $paramType);
            }
        }
    }

    /**
     * Filters and sanitizes all query parameters defined in route's query config option.
     * All specified parameters which were not present in the matched route will be set to null.
     */
    private function setQueryParams()
    {
        if (empty($_GET) || empty($this->route['query'])) {
            return;
        }

        foreach ($this->route['query'] as $paramName => $paramType) {
            try {
                $sanitizedParam = InputSanitizer::sanitize($_GET[$paramName], $paramType);

                // If value for given parameter was already provided, treat is as array
                if (!empty($this->query[$paramName])) {
                    if (!is_array($this->query[$paramName])) {
                        $this->query[$paramName] = [$this->query[$paramName]];
                    }

                    $this->query[$paramName][] = $sanitizedParam;
                } else {
                    $this->query[$paramName] = $sanitizedParam;
                }
            } catch (MishandledInputException $m) {
                $this->query[$paramName] = null;
            }
        }
    }

    /**
     * Searches user-submitted URL (stripped of any query parameters) in array of "static" routes.
     * If route is matched, sets it as class $route field. Otherwise, searches in array of regex routes.
     * @param string $url URL query
     * @return bool Indicates, whether the URL was matched
     * @throws MishandledInputException
     */
    private function matchRoute($url)
    {
        $url = explode('?', $url)[0];
        if (array_key_exists($url, ROUTES)) {
            $this->route = ROUTES[$url];
            $this->setQueryParams();
            return true;
        } else {
            foreach (REGEX_ROUTES as $regex => $route) {
                if (!empty(preg_match('/^' . $regex . '\/?$/', $url, $matches))) {
                    $this->route = $route;
                    $this->setParams($matches);
                    $this->setQueryParams();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $routeRegex
     * @param array $parameterTypes
     * @param array $generatorParams
     * @return string
     */
    private function parseRegexRoute($routeRegex, $parameterTypes, $generatorParams)
    {
        $route = str_replace('\/', '/', $routeRegex);

        foreach ($parameterTypes as $paramName => $paramType) {
            $route = preg_replace('/\(\?<' . $paramName . '>.+\)/', $generatorParams[$paramName], $route);
        }

        return $route;
    }

    public function parseQueryAndFragment($generatorParams = [])
    {
        $route = '';

        if (!empty($generatorParams['query']) && is_array($generatorParams['query'])) {
            $route .= '?';
            foreach ($generatorParams['query'] as $queryParamKey => $queryParamValue) {
                if (is_array($queryParamValue)) {
                    foreach ($queryParamValue as $arrayParamValue) {
                        $route .= $queryParamKey . '[]=' . $arrayParamValue . '&';
                    }
                } else {
                    $route .= $queryParamKey . '=' . $queryParamValue . '&';
                }
            }
            $route = rtrim($route, '&');
        }

        if (!empty($generatorParams['fragment']) && is_string($generatorParams['fragment'])) {
            $route .= '#' . $generatorParams['fragment'];
        }

        return $route;
    }

    /**
     * @param $name
     * @param array $generatorParams
     * @param bool $absoluteUrl
     * @return string
     */
    public function generate($name, $generatorParams = [], $absoluteUrl = false)
    {
        $domain = $absoluteUrl ? Config::PROJECT_URL : '';

        foreach (ROUTES as $route => $routeParams) {
            if ($routeParams['name'] === $name) {
                return $domain . $route . $this->parseQueryAndFragment($generatorParams);
            }
        }

        foreach (REGEX_ROUTES as $routeRegex => $routeParams) {
            if ($routeParams['name'] === $name) {
                return $domain .
                    $this->parseRegexRoute($routeRegex, $routeParams['parameters'], $generatorParams) .
                    $this->parseQueryAndFragment($generatorParams);
            }
        }

        if (!empty(Config::ROUTER_FALLBACK)) {
            return $domain . Config::ROUTER_FALLBACK;
        }

        return '';
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }
}
