<?php

namespace Novvai\Model;

use Novvai\Container;
use Novvai\Stacks\Stack;
use Novvai\Interfaces\Arrayable;
use Novvai\Stacks\Interfaces\Stackable;
use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class Base implements Arrayable
{

    protected $visible = ['*'];

    /**
     * @var QueryBuilderInterface
     */
    protected $builder;
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


    public function all(): Stackable
    {
        $this->builder->setSelectableFields($this->visible);

        return $this->get();
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
        $result =  $this->connection->getBy($query);

        return $this->wrap($result);
    }

    private function wrap(array $collectedData): Stackable
    {
        $stack = Stack::make();
        if (count($collectedData) == 0) {
            return $stack;
        }
        $fields = $this->getAvailableFields(reset($collectedData));
        $collective = self::buildCollective($fields, $collectedData);

        return $stack->collect($collective);
    }

    private static function getAvailableFields($item)
    {
        return array_keys($item);
    }

    private static  function buildCollective($fields, $collectedData)
    {
        $collective = [];
        foreach($collectedData as $resultItem){
            $class = Container::make(self::class);

            foreach ($fields as $field) {
                $class->{$field} = $resultItem[$field];
            }

            $collective[] = $class;
        }
    
        return $collective;
    }

    public function toArray()
    {
        return get_public_vars($this);
    }

}
