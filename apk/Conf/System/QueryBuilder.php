<?php namespace Kyaaaa\System;

use Apk\Conf\Connection;

class DB
{
    public static $instance;

    /**
     *	@var $table, store model table
     */
    private static $table;

    /**
     *	@var $data,
     */
    private $data;

    /**
     *	@var $query, store sql database query
     */
    private $query;

    /**
     *	@var $db, hold database connection
     */
    private $db;

    private function __construct ()
    {
        // Set database connection
        $connect = new Connection();
        $this->db = $connect->PDO();
        $this->whereCollection = [];
    }

    /**
     *	Build SELECT query.
     *
     *	@param string $columns
     *
     *	@return $this
     */
    public function select($columns)
    {
        $this->query = "SELECT ".$columns." FROM ".static::$table."";
        return $this;
    }

    /**
     *	SELECT all query.
     *
     *	@return $this
     */
    public function all()
    {
        $this->query = "SELECT * FROM ".static::$table."";
        return $this;
    }

    public function truncate()
    {
        $this->query = "TRUNCATE TABLE ".static::$table."";
        return $this;
    }

    public function useIndex($index)
    {
        $this->query .= " USE INDEX (".$index.")";
        return $this;
    }

    public function forceIndex($index)
    {
        $this->query .= " FORCE INDEX (".$index.")";
        return $this;
    }

    public function ignoreIndex($index)
    {
        $this->query .= " IGNORE INDEX (".$index.")";
        return $this;
    }

    /**
     *	Add INNER JOIN.
     *
     *	@param string $table
     *	@param string $column1
     *	@param string $operator
     *	@param string $column2
     *
     *	@return $this
     */
    public function join($table, $column1, $operator, $column2)
    {
        $this->query .= " INNER JOIN ".$table." ON ".$column1." ".$operator." ".$column2."";
        return $this;
    }

    /**
     *	Add LEFT JOIN.
     *
     *	@param string $table
     *	@param string $column1
     *	@param string $operator
     *	@param string $column2
     *
     *	@return $this
     */
    public function leftJoin($table, $column1, $operator, $column2)
    {
        $this->query .= " LEFT JOIN ".$table." ON ".$column1." ".$operator." ".$column2."";
        return $this;
    }

    /**
     *	Add RIGHT JOIN.
     *
     *	@param string $table
     *	@param string $column1
     *	@param string $operator
     *	@param string $column2
     *
     *	@return $this
     */
    public function rightJoin($table, $column1, $operator, $column2)
    {
        $this->query .= " RIGHT JOIN ".$table." ON ".$column1." ".$operator." ".$column2."";
        return $this;
    }

    /**
     *	Add CROSS JOIN.
     *
     *	@param string $table
     *
     *	@return $this
     */
    public function crossJoin($table)
    {
        $this->query .= " CROSS JOIN ".$table."";
        return $this;
    }

    /**
     *	Add UNION.
     *
     *	@param string $table
     *	@param string $columns
     *
     *	@return $this
     */
    public function union($table, $columns)
    {
        $this->query .= " UNION SELECT ".$columns." FROM ".$table."";
        return $this;
    }

    /**
     *	Add UNION ALL.
     *
     *	@param string $table
     *	@param string $columns
     *
     *	@return $this
     */
    public function unionAll($table, $columns)
    {
        $this->query .= " UNION ALL SELECT ".$columns." FROM ".$table."";
        return $this;
    }

    /**
     *	Add a WHERE condition.
     *
     *	@param string $columns
     *	To add custom operator use it after column with space.
     *	@param string $value
     *
     *	@return $this
     */
    public function where($column, $value)
    {

        $totalWhereCollection = count($this->whereCollection);
        if ($totalWhereCollection < 1) {
            $this->makeSelfQuery("WHERE", $column, $value);
        } else {
            $this->makeSelfQuery("AND", $column, $value);
        }
        array_push($this->whereCollection, $totalWhereCollection + 1);

        return $this;
    }

    /**
     * @param $query
     * @return void
     */
    private function makeSelfQuery($query, $column, $value): void {
        if (empty(explode(' ', $column)[1])) {
            $this->query .= " ".$query." ".$column." = '".$value."'";
        } else {
            $this->query .= " ".$query." ".$column." '".$value."'";
        }
    }

    /**
     *	WHERE IS NULL.
     *
     *	@param string $columns
     *
     *	@return $this
     */
    public function whereIsNull($column)
    {
        $this->query .= " WHERE ".$column." IS NULL";
        return $this;
    }

    /**
     *	WHERE IS NOT NULL.
     *
     *	@param string $columns
     *
     *	@return $this
     */
    public function whereIsNotNull($column)
    {
        $this->query .= " WHERE ".$column." IS NOT NULL";
        return $this;
    }


    /**
     *	Add a WHERE condition with LIKE Operator.
     *
     *	@param string $columns
     *	@param string $pattern
     *
     *	@return $this
     */
    public function whereLike(string $column, string $pattern)
    {
        $this->query .= " WHERE ".$column." LIKE "."'".$pattern."'";
        return $this;
    }

    /**
     *	Add a WHERE condition with IN Operator.
     *
     *	@param string $columns
     *	@param string $value
     *
     *	@return $this
     */
    public function whereIn(string $column, $value)
    {
        if (is_array($value)) {
            $value = implode("', '", $value);
            $this->query .= " WHERE ".$column." IN ('".$value."')";
        } else {
            $this->query .= " WHERE ".$column." IN (".$value.")";
        }

        return $this;
    }

    /**
     *	Add OR operator.
     *
     *	@param string $columns
     *	@param string $operator
     *	@param string $value
     *
     *	@return $this
     */
    public function or($column, $operator, $value)
    {
        $this->query .= " OR ".$column." ".$operator." '".$value."'";
        return $this;
    }

    /**
     *	Add AND operator.
     *
     *	@param string $columns
     *	@param string $operator
     *	@param string $value
     *
     *	@return $this
     */
    public function and($column, $value)
    {
        if (empty(explode(' ', $column)[1])) {
            $this->query .= " AND ".$column." = '".$value."'";
        } else {
            $this->query .= " AND ".$column." '".$value."'";
        }
        return $this;
    }

    /**
     *	Add a GROUPBY condition.
     *
     *	@param string $columns
     *
     *	@return $this
     */
    public function groupBy($columns)
    {
        $this->query .= " GROUP BY ".$columns."";
        return $this;
    }

    /**
     *	Add a HAVING condition.
     *
     *	@param string $columns
     *	@param string $operator
     *	@param string $value
     *
     *	@return $this
     */
    public function having($column, $operator, $value)
    {
        $this->query .= " HAVING ".$column." ".$operator." '".$value."'";

        return $this;
    }

    /**
     *	Add a ORDERBY constraint.
     *
     *	@param string $columns
     *	@param string $order (optional)
     *
     *	@return $this
     */
    public function orderBy($columns, $order = 'DESC')
    {
        $this->query .= " ORDER BY ".$columns." ".strtoupper($order)."";
        return $this;
    }

    /**
     *	Add a LIMIT constraint.
     *
     *	@param integer $number
     *
     *	@return $this
     */
    public function limit($number)
    {
        $this->query .= ' LIMIT '.$number.'';
        return $this;
    }

    /**
     *	Build DELETE query.
     *
     *	@return $this
     */
    public function delete()
    {
        $this->query = 'DELETE FROM '.static::$table.'';
        return $this;
    }

    /**
     *	Build INSERT query.
     *
     *	@param array $data
     */
    public function insert(array $data)
    {
        $this->data = $data;

        $this->query = 'INSERT INTO '. static::$table . ' (';

        foreach ($data as $column => $value) {
            $this->query .= $column.', ';
        }

        $this->query = trim($this->query, ', ');
        $this->query .= ') VALUES (';

        foreach ($data as $column => $value) {
            $this->query .= ':'.$column.', ';
        }

        $this->query = trim($this->query, ', ');
        $this->query .= ')';

        return $this;
    }

    /**
     *	Build UPDATE query.
     *
     *	@param array $data
     */
    public function update(array $data)
    {
        $this->data = $data;

        $this->query = 'UPDATE ' . static::$table . ' SET ';

        foreach ($data as $column => $value) {
            $this->query .= $column . " = :". $column . ", ";
        }

        $this->query = trim($this->query, ', ');

        return $this;
    }

    /**
     *	Write SQL query.
     *
     *	@param string $query
     *
     *	@return object
     */
    public function custom($query)
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $data = $stmt->fetchAll();

        return $data;
    }

    public function get()
    {
        $stmt  = $this->db->prepare($this->query);
        $stmt->execute();

        $data = $stmt->fetchAll();

        return $data;
    }

    public function first()
    {
        $stmt = $this->db->prepare($this->query);
        $stmt->execute();

        $data = $stmt->fetchColumn();

        return $data;
    }

    public function save()
    {
        $stmt = $this->db->prepare($this->query);

        foreach ($this->data as $column => $value) {
            $stmt->bindValue(':'.$column, $value);
        }

        $stmt->execute();
    }

    public static function query ($table = ''): DB
    {
        if ($table !== ''){
            static::$table = $table;
        }

        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}

?>