<?php

namespace Barebones\App;

use Barebones\Classes\Singleton;
use Barebones\Constants\DatabaseTypeConstant;
use Exception;
use PDO;

class Database extends Singleton
{
    /** @var PDO|null */
    private $connection;

    /**
     * Database constructor.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();

        try {
            switch (Config::DB_TYPE) {
                case DatabaseTypeConstant::MYSQL:
                case DatabaseTypeConstant::POSTGRESQL:
                    $this->connection = new PDO(
                        Config::DB_TYPE . ':host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME,
                        Config::DB_USERNAME,
                        Config::DB_PASSWORD
                    );
                    break;
                case DatabaseTypeConstant::SQLITE:
                    $this->connection = new PDO(Config::DB_TYPE . ':' . APP_ROOT . Config::DB_HOST);
                    break;
                case DatabaseTypeConstant::NONE:
                default:
                    return;
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            self::closeConnection();
            throw new Exception('Failed to connect to database: ' . $e->getMessage());
        }
    }

    /**
     * Returns active connection to the database
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Clears active database connection and destroy the instance of the class
     * @return void
     */
    public function closeConnection()
    {
        $this->connection = null;
        self::destroyInstance();
    }

    public function __destruct()
    {
        self::closeConnection();
    }
}
