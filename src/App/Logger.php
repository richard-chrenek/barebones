<?php

namespace Barebones\App;

use Barebones\Classes\Interfaces\LoggerInterface;
use Barebones\Classes\LoggerMessage;
use Barebones\Classes\Singleton;

class Logger extends Singleton implements LoggerInterface
{
    protected $logStack = [
        LoggerInterface::DEBUG => [],
        LoggerInterface::INFO => [],
        LoggerInterface::WARNING => [],
        LoggerInterface::ERROR => [],
    ];

    public function log($message, $context = [], $level = LoggerInterface::INFO)
    {
        $this->logStack[$level] = new LoggerMessage($message, $context);
    }

    public function debug($message, $context = [])
    {
        $this->log($message, $context, $this::DEBUG);
    }

    public function warning($message, $context = [])
    {
        $this->log($message, $context, $this::WARNING);
    }

    public function error($message, $context = [])
    {
        $this->log($message, $context, $this::ERROR);
    }

    /**
     * @return array[]
     */
    public function getLogStack() {
        return $this->logStack;
    }
}
