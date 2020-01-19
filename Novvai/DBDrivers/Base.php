<?php

namespace Novvai\DBDrivers;

use Exception;
use Novvai\DBDrivers\Interfaces\DBConnectionInterface;

abstract class Base implements DBConnectionInterface
{
    protected static $_configPath = __DIR__ . "/config/db.php";

    protected static $config = null;

    /**
     * @var PDO;
     */
    protected $connection;

    /**
     * 
     */
    public function __construct()
    {
        $this->loadConfig();
        $this->dbConnect();
    }

    /**
     * Overwrites the default configuration file path
     * 
     * @param string $path
     * 
     * @return void
     */
    public static function setConfigPath(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("Config file not found", 40003);
        }
        static::$_configPath = $path;
    }

    /**
     * Attempts to make a DB connection
     * 
     * @return void
     */
    abstract protected function dbConnect(): void;

    /**
     * Loads default DataBase credentials
     * 
     * @return void
     */
    private function loadConfig(): void
    {
        if (is_null(static::$config)) {
            static::$config = require_once(static::$_configPath);
        }   
    }
}
