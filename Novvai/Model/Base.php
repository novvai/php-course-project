<?php

namespace Novvai\Model;

use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class Base
{
    /**
     * @var DBConnectionInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName = "";

    public function __construct(DBConnectionInterface $connection, QueryBuilderInterface $builder)
    {
        $this->builder = $builder;
        $this->connection = $connection;
        $this->bootstrap();
    }


    public function all(): array
    {
        $query = $this->builder->all();
        return $this->connection->getBy($query);
    }

    private function dbSetup()
    {
        $this->builder->setTableName($this->getTableName());
    }

    private function getTableName(): string
    {
        return $this->tableName;
    }

    private function setTableName(): void
    {
        $this->tableName = $this->tableName != "" ? $this->tableName : $this->extractTableName(get_class($this));
    }

    private function bootstrap()
    {
        $this->setTableName();
        $this->dbSetup();
    }

    private function extractTableName(string $className)
    {
        $cl = end(explode('\\', $className));
        preg_match_all('/[A-Z]/', $cl, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $replacer = ($i ?  "_" : "") . strtolower($matches[0][$i]);
            $cl = preg_replace("/{$matches[0][$i]}/s", $replacer, $cl, 1);
        }

        return plural($cl);
    }

    public function andWhere(...$args): Base
    { 
        $this->builder->andWhere(...$args);

        return $this;
    }
    public function orWhere(...$args): Base
    { 
        $this->builder->orWhere(...$args);

        return $this;
    }

    /**
     * 
     */
    public function where(...$args): Base
    {
        $this->builder->where(...$args);
        return $this;
    }

    public function get()
    {
        $this->builder->setSelectableFields($this->visible);
        $this->builder->buildQuery();
        $query = $this->builder->getQuery();

        return $this->connection->getBy($query);
    }
}
