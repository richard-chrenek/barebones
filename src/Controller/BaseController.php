<?php

namespace Barebones\Controller;

use Barebones\App\Database;
use PDO;

class BaseController
{
    /** @var array */
    protected $query = [];

    public function __construct()
    {
    }

    /**
     * "Shorthand" method for accessing the active DB connection.
     * @return PDO
     */
    protected function getDB()
    {
        return Database::getInstance()->getConnection();
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQueryParameter($name, $default = null)
    {
        return empty($this->query[$name]) ? $default : $this->query[$name];
    }
}
