<?php

namespace Barebones\App;

use Barebones\Classes\Interfaces\AfterActionInterface;
use Barebones\Classes\Interfaces\BeforeActionInterface;
use Barebones\Classes\Singleton;
use Barebones\Controller\ErrorController;
use Exception;

class App extends Singleton
{
    /**
     * Core method of the framework, which handles application lifecycle.
     * First, it tries to parse the user submitted URL into separate controller, action, and params entries. These are
     * then used to instantiate the business logic of the route.
     * If any unhandled Exception bubbles into this method, it is delegated and handled by ErrorController instance.
     * @return void
     */
    public function run()
    {
        session_start();
        try {
            $router = Router::getInstance();
            $router->match();
            $controller = $router->getController();
            $action = $router->getAction();
            $params = $router->getParameters();

            $controllerInstance = new $controller();
            $controllerInstance->setQuery($router->getQuery());

            if ($controllerInstance instanceof BeforeActionInterface) {
                $controllerInstance->beforeAction();
            }

            $controllerInstance->$action(...$params);

            if ($controllerInstance instanceof AfterActionInterface) {
                $controllerInstance->afterAction();
            }
        } catch (Exception $e) {
            new ErrorController($e);
        }
    }
}
