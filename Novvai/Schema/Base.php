<?php

namespace Novvai\Schema;

use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

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

    abstract public function handle();
    abstract public function rollback();

    public function up($tableName, callable $fn)
    {
        $builder = $this->builder->startMigration($tableName);
        $fn($builder);
        $builder->finishMigration();
        $this->connection->execute($builder->getQuery());
    }
    public function down(callable $fn)
    {
        $fn($this->builder);

        $this->connection->execute($this->builder->getQuery());
    }
}
