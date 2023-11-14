<?php

namespace Barebones\Classes\Interfaces;

interface BeforeActionInterface
{
    /**
     * If there is a need to run some logic before each route action call in controller, you can implement this method.
     * @return void
     */
    public function beforeAction();
}
