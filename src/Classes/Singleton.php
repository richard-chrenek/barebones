<?php

namespace Barebones\Classes;

/**
 * Singleton pattern abstract class for reuse within application
 * @ref https://en.wikipedia.org/wiki/Singleton_pattern
 */
abstract class Singleton
{
    /**
     * Array of all instantiated Singleton classes
     * @var static[]
     */
    private static $instances = [];

    /** Constructor has private visibility to match with pattern and to prevent creation of the multiple Singleton classes */
    protected function __construct()
    {
    }

    /**
     * Returns single instance of Singleton class.
     * If this class was not instantiated yet, creates and stores a new instance of it.
     * @return static
     */
    public static function getInstance()
    {
        if (isset(self::$instances[get_called_class()])) {
            return self::$instances[get_called_class()];
        }

        self::$instances[get_called_class()] = new static();

        return self::$instances[get_called_class()];
    }

    /**
     * Destroys the instance of the Singleton class based on the caller class name.
     */
    final protected static function destroyInstance()
    {
        $callerClass = get_called_class();
        unset(self::$instances[$callerClass]);
    }
}
