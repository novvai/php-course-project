<?php

namespace Novvai\DBDrivers;

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
        $db_type = $this->config['connection'];
        list($host, $port, $db_name, $user, $pass) = array_values($this->config[$db_type]);

        $dsn = "$db_type:host=$host;dbname=$db_name;port=$port";

        $this->connection = new PDO($dsn, $user, $pass, self::DRIVER_OPTIONS);
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
        return $this->connection->exec($query);
    }
}
