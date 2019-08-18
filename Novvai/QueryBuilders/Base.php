<?php

namespace Novvai\QueryBuilders;

use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;
use DateTime;

abstract class Base implements QueryBuilderInterface
{
    /**
     * All Fields that should be visible
     */
    protected $selectedFields = ['*'];

    protected $driver;

    protected $hasOneColumn = false;
    protected $shouldUseTimeStamps = true;
    
    /**
     * Main query parameters
     * 
     * @var string
     */
    protected $query = '';
    
    /**
     * Additional query parameters like LIMIT and ORDER
     * 
     * @var string
     */
    protected $queryAdditions = '';

    /**
     * Name of the table that the queries
     * are going to be executed on
     * 
     * @var string
     */
    protected $tableName = "";


    /**
     * @param string $name
     * @return void
     */
    public function setTableName(string $name): void
    {
        $this->tableName = $name;
    }
    public function setSelectableFields(array $fields): void
    {
        $this->selectedFields = $fields;
    }

    protected function shouldStartNewCollumn()
    {
        if (!$this->hasOneColumn) {
            $this->hasOneColumn = true;
        } else {
            $this->next();
        }
    }


    /**
     * Creates comma separated list of all selectable fields
     * 
     * @return string
     */
    protected function getSelectableFields(): string
    {
        return implode(',', $this->selectedFields);
    }

    /**
     * Creates comma separated strings for collumn and values 
     * of the record for Query
     * 
     * @param array $data
     * @param bool $creating
     */
    protected function normalize(array $data, bool $creating = false):array
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $this->appendTimeStamps($columns, $values, $creating);

        $values = $this->typeNormalization($values);

        return [implode(",", $columns), implode(",", $values)];
    }

    /**
     * Adds created and updated at fields implicitly
     * 
     * @param array $collumns
     * @param array $values
     */
    protected function appendTimeStamps(&$collumns, &$values, $creating = false)
    {
        if (!$this->shouldUseTimeStamps) {
            return;
        }

        $currentDate = (new DateTime())->format("Y-m-d H:i:s");
        $collumns[] = "updated_at";
        $values[] = $currentDate;
        if ($creating) {
            $collumns[] = "created_at";
            $values[] = $currentDate;
        }
    }

    /**
     * Normalizes the values for DB query use
     * adds single quotes at the strings 
     * 
     * @param array $data
     * 
     * @return array
     */
    protected function typeNormalization(array $data) : array
    {
        return map($data, function ($item) {
            return is_numeric($item) ? $item : "'$item'";
        });
    }
}
