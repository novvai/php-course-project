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

    protected $disableTimeStamps = false;

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

    /**
     * Attempts to create record from given data,
     * or data that is stored on the initialez model
     * 
     * @param mixed $createInfo
     * 
     * @return self|null
     */
    public function create($createInfo = null)
    {
        $createInfo = $createInfo ?: get_public_vars($this);

        $this->builder->create($createInfo);

        $this->connection->execute($this->builder->getQuery());

        return $this->wrap([$createInfo])->first();
    }

    /**
     * Attempts to create record from given data,
     * or data that is stored on the initialez model
     * 
     * @param mixed $createInfo
     * 
     * @return self|null
     */
    public function delete($deleteData = null)
    {
        $deleteData = $deleteData ?: get_public_vars($this);

        $this->builder->delete($deleteData);

        return $this->connection->execute($this->builder->getQuery());
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

    private function setTimeStampOptions()
    {
        $this->disableTimeStamps ? $this->builder->disableTimeStamps() : null;
    }

    private function setTableName(): void
    {
        $this->tableName = $this->tableName != "" ? $this->tableName : $this->extractTableName(get_class($this));
    }

    private function bootstrap()
    {
        $this->setTimeStampOptions();
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

    private static function buildCollective($fields, $collectedData)
    {
        $collective = [];
        foreach ($collectedData as $resultItem) {
            $class = Container::make(static::class);

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
