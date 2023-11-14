<?php

namespace Barebones\Classes;

class LoggerMessage
{
    protected $message;
    protected $context;

    /**
     * @param string $message
     * @param array $context
     */
    public function __construct($message, array $context = [])
    {
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
