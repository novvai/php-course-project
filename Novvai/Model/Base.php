<?php

namespace Novvai\Model;

use Traversable;
use Novvai\Container;
use Novvai\Stacks\Stack;
use Novvai\Interfaces\Arrayable;
use Novvai\Model\Traits\Relations;
use Novvai\Stacks\Interfaces\Stackable;
use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

class Base implements Arrayable
{
    use Relations;

    /**
     * List of fields that should be retrived by the query
     */
    protected $retrievable = ['*'];
    /**
     * List of fields that are retrieved but should not be displayed
     * in the final response
     */
    protected $private = [];

    protected $uniqueIdentifier = "id";


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

    /**
     * Has the DB booted
     * @var bool
     */
    private $booted = false;

    public function __construct()
    {
        $this->bootstrap();
    }

    /**
     * @param string $methodName
     * @param array $arguments
     * 
     * @return self
     */
    public function __call($methodName, $arguments)
    {
        $this->dbSetup();
        $this->builder->$methodName(...$arguments);

        return $this;
    }


    /**
     * Attempts to update record from given data,
     * or data that is stored on the initialized model
     * 
     * @param mixed $updateInfo
     * 
     * @return Stackable
     */
    public function update($updateInfo = null)
    {
        $this->dbSetup();
        // ["username"=>1]
        $identifier = [$this->uniqueIdentifier => $this->{$this->uniqueIdentifier}];
        $updateInfo = $updateInfo ?: get_public_vars($this);

        $this->builder->update($identifier, $updateInfo);

        return $this->dbWrite($updateInfo);
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
        $this->dbSetup();

        $createInfo = $createInfo ?: get_public_vars($this);

        $this->builder->create($createInfo);

        return $this->dbWrite($createInfo, true);
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
        $this->dbSetup();

        $deleteData = $deleteData ?: get_public_vars($this);

        $this->builder->delete($deleteData);

        $deleteQuery = $this->builder->getQuery();

        $this->builder->unsetQuery();

        return $this->connection->execute($deleteQuery);
    }

    /**
     * Changes what fields are retrieved
     * 
     * @param array $fields
     * 
     * @return self
     */
    public function select(array $fields): Base
    {
        $this->retrievable = $fields;
        return $this;
    }
    /**
     * @return Novvai\Stacks\Stack
     */
    public function all(): Stackable
    {
        return $this->get();
    }

    public function count()
    {
        $this->dbSetup();

        $query = $this->builder->getCountQuery();
        $result =  $this->connection->getBy($query);
        $result = reset($result);

        return $result['counted'];
    }

    /**
     * @return Novvai\Stacks\Stack
     */
    public function get(): Stackable
    {
        $this->dbSetup();

        $this->builder->setSelectableFields($this->retrievable);
        $this->builder->buildQuery();
        $query = $this->builder->getQuery();
        $this->builder->unsetQuery();

        $result =  $this->connection->getBy($query);

        return $this->wrap($result);
    }

    /**
     * Initialize execution of Write to db
     * 
     * @param array $data
     * 
     * @return Stackable
     */
    private function dbWrite($data, $newRecord = false)
    {
        /** @var void|array $dbResponse */
        $dbResponse = $this->connection->execute($this->builder->getQuery());
        $this->builder->unsetQuery();

        if ($dbResponse != 1) {
            return $this->handleError($dbResponse);
        }
        if ($newRecord === true) {
            $data[$this->uniqueIdentifier] =  $this->connection->lastInsertId();
        }

        return $this->wrap([$data]);
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
    }

    /**
     * @return void
     */
    private function dbSetup()
    {
        if (!$this->booted) {
            $this->booted = true;
            $this->builder = Container::makeFromBinding(QueryBuilderInterface::class);
            $this->connection = Container::makeFromBinding(DBConnectionInterface::class);

            $this->builder->setTableName($this->getTableName());
        }
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
            'errors' => $this->mapError((int) $err['code']),
            'original_err' => $err['message']
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
                if ($pubFields[$name] instanceof Traversable) {
                    unset($pubFields[$name]);
                }
                if ($pubFields[$name] instanceof Arrayable) {
                    $pubFields[$name] = $pubFields[$name]->toArray();
                }

                foreach ($pubFields[$name] as $key => $field) {
                    if ($pubFields[$name][$key] instanceof Arrayable) {
                        $pubFields[$name][$key] = $field->toArray();
                    }
                }
            }
        }
        return $pubFields;
    }
}
