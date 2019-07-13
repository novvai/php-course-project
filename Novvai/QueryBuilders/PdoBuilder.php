<?php

namespace Novvai\QueryBuilders;

use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;
use InvalidArgumentException;

class PdoBuilder extends Base
{
    /**
     * Creates Query string fetching all entries from give Table
     * by given single constraint
     * 
     * @return string
     */
    public function where(...$args): QueryBuilderInterface
    {
        $this->query .= " WHERE ";
        $this->query .= $this->getConstraintFormatted($args);

        return $this;
    }

    /**
     * Creates Query string fetching all entries from give Table
     * adding a constraint with AND statement
     * 
     * @return string
     */
    public function andWhere(...$args): QueryBuilderInterface
    {
        $this->query .= " AND ";
        $this->query .= $this->getConstraintFormatted($args);

        return $this;
    }

    /**
     * Creates Query string fetching all entries from give Table
     * adding a constraint with OR statement
     * 
     * @return string
     */
    public function orWhere(...$args): QueryBuilderInterface
    {
        $this->query .= " OR ";
        $this->query .= $this->getConstraintFormatted($args);

        return $this;
    }



    /**
     * Creates Query string fetching all entries from give Table
     * 
     * @return string
     */
    public function all(): string
    {
        $this->buildQuery();

        return $this->getQuery();
    }

    /**
     * Sets Set Primary key with auto increment
     * 
     * @param string $name = "id"
     * 
     * @return self
     */
    public function autoIncriment(string $name = "id"): QueryBuilderInterface
    {
        $this->shouldStartNewCollumn();

        $this->query .= "$name INT(11) AUTO_INCREMENT PRIMARY KEY ";
        return $this;
    }

    /**
     * @return self
     */
    public function next(): QueryBuilderInterface
    {
        $this->query .= ',';
        return $this;
    }

    /**
     * @return self
     */
    public function notNull(): QueryBuilderInterface
    {
        $this->query .= " NOT NULL ";
        return $this;
    }

    /**
     * @return self
     */
    public function string(int $max): QueryBuilderInterface
    {
        $this->query .= " VARCHAR($max) ";
        return $this;
    }

    /**
     * @return self
     */
    public function text(): QueryBuilderInterface
    {
        $this->query .= " TEXT ";

        return $this;
    }

    /**
     * 
     * 
     * @return self
     */
    public function default($defaultValue): QueryBuilderInterface
    {
        $this->query .= " DEFAULT $defaultValue ";
        return $this;
    }

    /**
     * @return self
     */
    public function integer(int $max): QueryBuilderInterface
    {
        $this->query .= " INT ($max) ";
        return $this;
    }
    /**
     * @return self
     */
    public function decimal($max, $points): QueryBuilderInterface
    {
        $this->query .= " DECIMAL ($max, $points) ";
        return $this;
    }

    /**
     * @return self
     */
    public function unique(...$columns): QueryBuilderInterface
    {
        $columns = implode(', ', $columns);
        $this->next();
        $this->query .= " UNIQUE KEY ($columns)";
        return $this;
    }

    /**
     * @return self
     */
    public function float($max, $points): QueryBuilderInterface
    {
        $this->query .= " FLOAT ($max, $points) ";
        return $this;
    }

    /**
     * @return self
     */
    public function startMigration(string $tableName): QueryBuilderInterface
    {
        $this->query = "CREATE TABLE $tableName (";
        return $this;
    }

    /**
     * @return self
     */
    public function addColLumn(string $collumn): QueryBuilderInterface
    {
        $this->shouldStartNewCollumn();
        $this->query .= $collumn;

        return $this;
    }

    /**
     * Finish migration statement
     * 
     * @return self
     */
    public function finishMigration(): QueryBuilderInterface
    {
        $this->query .= ");";
        return $this;
    }

    /**
     * Retrieves the full query
     * 
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
    
    /**
     * Basic select query without constraints
     */
    public function buildQuery(): QueryBuilderInterface
    {
        $this->query = "SELECT {$this->getSelectableFields()} FROM {$this->tableName}" . $this->query;

        return $this;
    }

    /**
     * Creates correct format for constraint on the Query
     * 
     * @param array $args
     * 
     * @return string
     */
    private function getConstraintFormatted(array $args):string
    {
        $argCount = count($args);
        
        switch ($argCount) {
            case 2:
                return "{$args[0]}=" . (is_numeric($args[1]) ? $args[1] : "'{$args[1]}'");
            case 3:
                return "{$args[0]}{$args[1]}" . (is_numeric($args[2]) ? $args[2] : "'{$args[2]}'");
            default:
                throw new InvalidArgumentException("Where clause needs 2 or 3 arguments, $argCount given", 9000);
        }
    }
}
