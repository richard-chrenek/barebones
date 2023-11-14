<?php

namespace Barebones\Classes\Interfaces;

use Barebones\Classes\Singleton;

interface LoggerInterface
{
    const ERROR = 'error';
    const WARNING = 'warning';
    const INFO = 'info';
    const DEBUG = 'debug';

    public function log($message, $context = [], $level = LoggerInterface::INFO);
    public function debug($message, $context = []);

    public function warning($message, $context = []);

    public function error($message, $context = []);
}
