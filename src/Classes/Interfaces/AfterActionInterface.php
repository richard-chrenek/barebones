<?php

namespace Barebones\Classes\Interfaces;

interface AfterActionInterface
{
    /**
     * If there is a need to run some logic after each route action call in controller, you can implement this method.
     * @return void
     */
    public function afterAction();
}
