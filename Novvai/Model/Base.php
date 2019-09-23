<?php

namespace Novvai\Model;

use Novvai\Container;
use Novvai\Stacks\Stack;
use Novvai\Interfaces\Arrayable;
use Novvai\Stacks\Interfaces\Stackable;
use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;
use Traversable;

class Base implements Arrayable
{
    /**
     * List of fields that should be retrived by the query
     */
    protected $retrievable = ['*'];
    /**
     * List of fields that are retrieved but should not be displayed
     * in the final response
     */
    protected $private = [];

    

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
     * Adds pagination to the query
     * 
     * @param int $offset
     * @param int $limit = 10
     * @return self
     */
    public function paginate($offset, $limit = 10)
    {
        $this->builder->paginate($offset, $limit);

        return $this;
    }

    /**
     * Attempts to create record from given data,
     * or data that is stored on the initialized model
     * 
     * @param mixed $createInfo
     * 
     * @return Stackable
     */
    public function create($createInfo = null)
    {
        $createInfo = $createInfo ?: get_public_vars($this);

        $this->builder->create($createInfo);

        /** @var void|array $dbResponse */
        $dbResponse = $this->connection->execute($this->builder->getQuery());

        if ($dbResponse != 1) {
            return $this->handleError($dbResponse);
        }

        return $this->wrap([$createInfo]);
    }

    /**
     * Attempts to delete given record
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

    /**
     * @return Novvai\Stacks\Stack
     */
    public function all(): Stackable
    {
        return $this->get();
    }

    /**
     * @param array $args
     * 
     * @return self
     */
    public function andWhere(...$args): Base
    {
        $this->builder->andWhere(...$args);

        return $this;
    }

    /**
     * @param array $args
     * 
     * @return self
     */
    public function orWhere(...$args): Base
    {
        $this->builder->orWhere(...$args);

        return $this;
    }

    /**
     * @param array $args
     * 
     * @return self
     */
    public function where(...$args): Base
    {
        $this->builder->where(...$args);

        return $this;
    }

    /**
     * 
     */
    public function hasMany(string $className, string $identifier=null,string $on = null)
    {
        $identifier = $identifier?:'id';
        $child = Container::make($className);
        $relation_name = get_short_name($this);

        $on = $on?:$relation_name.'_id';

        return $this->{debug_backtrace()[1]['function']} = $child->where($on,$this->{$identifier})->all();
    }

    public function belongsTo(string $className, string $identifier=null, string $on = null)
    {
        
        $identifier = $identifier?:'id';
        $parent = Container::make($className);
        $relation_name = get_short_name($parent);

        $on = $on?:$relation_name.'_id';
        
        return $this->{debug_backtrace()[1]['function']} = $parent->where($identifier,$this->{$on})->get()->first();
    }

    /**
     * @return Novvai\Stacks\Stack
     */
    public function get(): Stackable
    {
        $this->builder->setSelectableFields($this->retrievable);
        $this->builder->buildQuery();
        $query = $this->builder->getQuery();

        $result =  $this->connection->getBy($query);

        return $this->wrap($result);
    }

    /**
     * Wraps the data into Stack
     * 
     * @param array $collectedData
     * 
     * @return Novvai\Stacks\Stack
     */
    private function wrap(array $collectedData): Stackable
    {
        $stack = Stack::make();
        // check if there is anything the $collectedData
        if (count($collectedData) == 0) {
            return $stack;
        }

        $fields = $this->getAvailableFields(reset($collectedData));
        $collective = self::buildCollective($fields, $collectedData);

        return $stack->collect($collective);
    }

    /**
     * Extracts all keys from collected data
     * 
     * @param array $item
     * 
     * @return array
     */
    private static function getAvailableFields($item)
    {
        return array_keys($item);
    }

    /**
     * Builds an array of classes representing
     * DataBase records
     * 
     * @param array $fields
     * @param array $collectedData 
     * 
     * @return array
     */
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

    /**
     * It's all coming together
     * 
     * @return void
     */
    private function bootstrap()
    {
        $this->setTimeStampOptions();
        $this->setTableName();
        $this->dbSetup();
    }

    /**
     * @return void
     */
    private function dbSetup()
    {
        $this->builder->setTableName($this->getTableName());
    }

    /**
     * @return string
     */
    private function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return void
     */
    private function setTimeStampOptions()
    {
        $this->disableTimeStamps ? $this->builder->disableTimeStamps() : null;
    }

    /**
     * @return void
     */
    private function setTableName(): void
    {
        $this->tableName = $this->tableName != "" ? $this->tableName : $this->extractTableName(get_class($this));
    }

    /**
     * Generates table name, based on convention, from the Class name
     * convetion:
     *  - name = UserPermission
     *  - table = user_permissions
     * 
     * @param string
     * 
     * @return string
     */
    private function extractTableName(string $className): string
    {
        $cl = end(explode('\\', $className));
        preg_match_all('/[A-Z]/', $cl, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $replacer = ($i ?  "_" : "") . strtolower($matches[0][$i]);
            $cl = preg_replace("/{$matches[0][$i]}/s", $replacer, $cl, 1);
        }

        return plural($cl);
    }

    /**
     * 
     */
    private function handleError(array $err)
    {
        return Stack::make()->collect([
            'errors' => $this->mapError((int) $err['code'])
        ]);
    }

    private function mapError($code)
    {
        switch ($code) {
            case 23000:
                $response = [
                    'code' => 4003,
                    'message' => "Duplicated entry!"
                ];
                break;

            default:
                $response = [
                    'code' => 66669999,
                    'message' => "Banica Exception!"
                ];
                break;
        }
        return $response;
    }

    /**
     * @inheridoc
     */
    public function toArray()
    {
        $pubFields = get_public_vars($this);

        foreach ($pubFields as $name => $_) {
            if (in_array($name, $this->private)) {
                unset($pubFields[$name]);
            }
            if($pubFields[$name] instanceof Arrayable){
                $pubFields[$name] = $pubFields[$name]->toArray();
            }

            if($pubFields[$name] instanceof Traversable){
                foreach($pubFields[$name] as $key => $field){
                    if ($pubFields[$name][$key] instanceof Arrayable){
                        $pubFields[$name][$key] = $field->toArray();
                    }
                }
            }
        }
        return $pubFields;
    }
}
