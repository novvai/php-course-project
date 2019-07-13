<?php

namespace Novvai\Schema;

use Novvai\DBDrivers\Interfaces\DBConnectionInterface;

abstract class Base
{

    /** 
     * @var DBConnectionInterface
     */
    protected $connection;

    protected $tableName;

    public function __construct(
        DBConnectionInterface $connection,
        QueryBuilderInterface $builder
    ) {
        $this->connection = $connection;
        $this->builder = $builder;
    }

    public function up($tableName, callable $fn)
    {
        $builder = $this->builder->startMigration($tableName);
        $fn($builder);
        $builder->finishMigration();
        $this->connection->execute($builder->getQuery());
    }
}
