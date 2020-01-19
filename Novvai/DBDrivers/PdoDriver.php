<?php

namespace Novvai\DBDrivers;

use Exception;
use PDO;

class PdoDriver extends Base
{
    const DRIVER_OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    /**
     * Query String
     * 
     * @var string
     */
    private $query = "";

    /**
     * PDO Statement
     * 
     * @var string
     */
    private $queryStatement = "";

    /**
     * @inheridoc
     */
    protected function dbConnect(): void
    {
        $db_type = static::$config['connection'];

        extract(static::$config[$db_type]);
        $dsn = "$db_type:host=$host;dbname=$db_name;port=$port";

        $this->connection = new PDO($dsn, $username, $password, self::DRIVER_OPTIONS);
    }

    /**
     * Returns the last inserted id in given session
     * 
     * @return int
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Fetching all entries matching Query String
     * 
     * @param string $query
     * 
     * @return array
     */
    public function getBy(string $query): array
    {
        $queryStatement = $this->connection->query($query);

        return $queryStatement->fetchAll();
    }

    /**
     * Executes query againts DB
     * Used primarily with INSERT/UPDATE/CREATE/DELETE
     * 
     * @param string $query
     */
    public function execute(string $query)
    {
        try {
            return $this->connection->exec($query);
        } catch (Exception $e) {
            return ["code" => $e->getCode(), "message" => $e->getMessage()];
        }
    }
}
