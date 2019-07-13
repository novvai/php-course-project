<?php

namespace Novvai\QueryBuilders;

use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

abstract class Base implements QueryBuilderInterface
{
    /**
     * All Fields that should be visible
     */
    protected $selectedFields = ['*'];

    protected $driver;

    protected $hasOneColumn = false;

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
    public function setSelectableFields(array $fields):void
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
}
