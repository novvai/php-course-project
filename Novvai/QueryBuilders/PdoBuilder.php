<?php

namespace Novvai\QueryBuilders;

use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;
use InvalidArgumentException;

class PdoBuilder extends Base
{
    /**
     * Disables the timestamps auto appending
     * 
     * @return void
     */
    public function disableTimeStamps()
    {
        $this->shouldUseTimeStamps = false;
    }

    /**
     * Creates Query string fetching all entries from give Table
     * when given column is Null
     * 
     * @return string
     */
    public function whereIsNull(string $column): QueryBuilderInterface
    {
        $this->query .= " WHERE $column IS NULL";

        return $this;
    }

    /**
     * Creates Query string sorting records from given Table
     * 
     * @param string $column
     * @param string $direction
     * 
     * @return string
     */
    public function sortBy(string $column, string $direction = "ASC"): QueryBuilderInterface
    {
        $this->query .= " ORDER BY $column $direction";

        return $this;
    }
    /**
     * Creates Query string fetching all entries from give Table
     * by given single constraint
     * 
     * @return string
     */
    public function where(...$args): QueryBuilderInterface
    {
        $this->query .= (strpos($this->query, 'WHERE') === false) ? " WHERE " : " AND ";
        $this->query .= $this->getConstraintFormatted($args);

        return $this;
    }

    /**
     * Creates Query string fetching all entries from give Table
     * by given LIKE constraint
     * 
     * @return string
     */
    public function whereLike($column, $value): QueryBuilderInterface
    {
        $this->query .= (strpos($this->query, 'WHERE') === false) ? " WHERE " : " AND ";
        $this->query .= "$column LIKE '{$this->sanitize($value)}' ";

        return $this;
    }

    /**
     * Creates Query string fetching all entries from give Table
     * by given LIKE constraint
     * 
     * @return string
     */
    public function whereLikeFuzzy($column, $value): QueryBuilderInterface
    {
        $this->query .= (strpos($this->query, 'WHERE') === false) ? " WHERE " : " AND ";
        $this->query .= "$column LIKE '%$this->sanitize($value)%' ";

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
     * Adds limit to the query
     * 
     * @return self
     */
    public function paginate($offset, $limit = 10): QueryBuilderInterface
    {
        $this->queryAdditions = $this->queryAdditions . " LIMIT $offset , $limit ";

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
    public function autoIncrement(string $name = "id"): QueryBuilderInterface
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
    public function indexed(array $collumns): QueryBuilderInterface
    {
        $this->next();
        $this->query .= " INDEX (" . implode(',', $collumns) . ") ";

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
    public function dateTime(): QueryBuilderInterface
    {
        $this->query .= " DATETIME ";

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
    public function boolean(): QueryBuilderInterface
    {
        $this->query .= " BOOLEAN ";
        return $this;
    }

    /**
     * Query that adds record to given DB
     * 
     * @param array $createInfo
     * 
     * @return self
     */
    public function create(array $createInfo): QueryBuilderInterface
    {
        list($columns, $values) = $this->normalize($createInfo, true);

        $columns = implode(",", $columns);
        $values = implode(",", $values);

        $this->query = "INSERT INTO {$this->tableName} ({$columns}) VALUES ($values)";

        return $this;
    }

    /**
     * Query that update record to given DB
     * 
     * @param array $updateInfo
     * 
     * @return self
     */
    public function update($identifier, array $updateInfo): QueryBuilderInterface
    {
        list($columns, $values) = $this->normalize($updateInfo);
        $setter = [];
        foreach ($columns as $index => $column) {
            $setter[] = "{$column}={$values[$index]}";
        }

        $identifierKey = array_keys($identifier)[0];
        $identifierValue = $this->sanitize(reset($identifier));
        $setterString = implode(',', $setter);

        $this->query = "UPDATE {$this->tableName} SET  {$setterString} WHERE {$identifierKey}={$identifierValue}";

        return $this;
    }
    /**
     * Query that deletes record from DB
     * 
     * @param array $identifiers
     * 
     * @return self
     */
    public function delete(array $identifiers): QueryBuilderInterface
    {
        $queryParams = map($identifiers, function ($item, $key) {
            return "$key" . (is_numeric($item) ? "=$item" : (is_null($item) ? " is null" : "='" . $this->sanitize($item) . "'"));
        });
        $queryParams = implode(' and ', $queryParams);
        $this->query = "DELETE FROM {$this->tableName} WHERE $queryParams";

        return $this;
    }

    /**
     * Creates foreign key restriction
     * 
     * @param string $foreign_key
     * @param string $reference_table
     * @param string $reference_key
     * 
     * @return self 
     */
    public function foreignCascade(string $foreign_key, string $reference_table, string $reference_key): QueryBuilderInterface
    {
        $this->next();
        $this->query .= " FOREIGN KEY ($foreign_key) REFERENCES $reference_table($reference_key) ON DELETE CASCADE";
        return $this;
    }

    /**
     * @retunr self
     */
    public function drop(string $tableName): QueryBuilderInterface
    {
        $this->query = "DROP TABLE $tableName";
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
    public function addSoftDelete(): QueryBuilderInterface
    {
        $this->shouldStartNewCollumn();
        $this->query .= " deleted_at DATETIME NOT NULL ";

        return $this;
    }

    /**
     * @return self
     */
    public function addTimeStamps(): QueryBuilderInterface
    {
        $this->shouldStartNewCollumn();
        $this->query .= " created_at DATETIME NOT NULL , updated_at DATETIME NOT NULL ";

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
     * Sets the querystring to nothing ready for new query
     * 
     * @return QueryBuilderInterface
     */
    public function unsetQuery(): QueryBuilderInterface
    {
        $this->query = "";
        
        return $this;
    }

    /**
     * Basic select query without constraints
     */
    public function buildQuery(): QueryBuilderInterface
    {
        $this->query = "SELECT {$this->getSelectableFields()} FROM {$this->tableName}" . $this->query . $this->queryAdditions;

        return $this;
    }
    /**
     * Basic select query without constraints
     */
    public function getCountQuery()
    {
        return "SELECT COUNT(*) as counted FROM {$this->tableName}" . $this->query;
    }

    /**
     * Creates correct format for constraint on the Query
     * 
     * @param array $args
     * 
     * @return string
     */
    private function getConstraintFormatted(array $args): string
    {
        $argCount = count($args);

        switch ($argCount) {
            case 2:
                return "{$args[0]}=" . (is_numeric($args[1]) ? $args[1] : "'" . $this->sanitize($args[1]) . "'");
            case 3:
                return "{$args[0]}{$args[1]}" . (is_numeric($args[2]) ? $args[2] : "'" . $this->sanitize($args[2]) . "'");
            default:
                throw new InvalidArgumentException("Where clause needs 2 or 3 arguments, $argCount given", 9000);
        }
    }
}
