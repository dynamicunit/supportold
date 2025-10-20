<?php

class db
{
    private static $_instance = null;
    private $_pdo = null,
        $_query,
        $_error = false,
        $_results,
        $_last_insert_id,
        $_count = 0,
        $_total_count = 0;


    private function __construct()
    {
        try {
            $this->_pdo = new PDO(
                'mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/db'),
                config::get('mysql/username'),
                config::get('mysql/password')
            );
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getinstanace()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }

  public function query($sql, $params = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            foreach ($params as $index => $param) {
                $this->_query->bindValue($index + 1, $param);
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
                // If SQL_CALC_FOUND_ROWS is used, fetch the total count
                if (stripos($sql, 'SQL_CALC_FOUND_ROWS') !== false) {
                    $this->_total_count = $this->_pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
                }
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
   /*
     for query debugging

public function query($sql, $params = array())
{
    $this->_error = false;
    echo "SQL: " . $sql . "\n";
    echo "Params: " . print_r($params, true) . "\n";
    if ($this->_query = $this->_pdo->prepare($sql)) {
        foreach ($params as $index => $param) {
            $this->_query->bindValue($index + 1, $param);
        }
        if ($this->_query->execute()) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
            $this->_last_insert_id = $this->_pdo->lastInsertId();
            echo "Last Insert ID: " . $this->_last_insert_id . "\n";
        } else {
            $this->_error = true;
            $errorInfo = $this->_query->errorInfo();
            echo "Error Info: " . print_r($errorInfo, true) . "\n";
        }
    }
    return $this;
}
*/

public function begin_transaction()
    {
        return $this->_pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->_pdo->commit();
    }

    public function roll_back()
    {
        return $this->_pdo->rollBack();
    }

    public function last_insert_id()
    {
        return $this->_last_insert_id;
    }


    // get function 
    /* how to use 
$conditions = [
    'id' => ['=', 123],
    'status' => ['=', 'active'],
    'created_at' => ['<', '2024-05-19']
];

$result = $db->get('users', $conditions, 10);

   */
    public function get($table, $where = array(), $orderby = '', $limit = '', $offset = '')
    {
        return $this->action('SELECT SQL_CALC_FOUND_ROWS *', $table, $where, $orderby, $limit, $offset);
    }

    private function action($action, $table, $conditions = array(),  $orderby = '', $limit = '', $offset = '')
    {
        $operators = array("=", ">", "<", ">=", "<=", "<>", "!=", "LIKE", "IN", "NOT IN", "BETWEEN", "NOT BETWEEN");

        if (!empty($conditions) && is_array($conditions)) {
            $sql = "{$action} FROM {$table} WHERE ";

            $conditionClauses = [];
            $params = [];

            foreach ($conditions as $field => $condition) {
                if (is_array($condition) && count($condition) === 2) {
                    list($operator, $value) = $condition;
                    if (in_array($operator, $operators)) {
                        $conditionClauses[] = "{$field} {$operator} ?";
                        $params[] = $value;
                    }
                }
            }

            if (count($conditionClauses) > 0) {
                $sql .= implode(' AND ', $conditionClauses);

                if ($orderby !== '') {
                    $sql .= " ORDER BY {$orderby}";
                }

                if ($limit !== '') {
                    $sql .= " LIMIT {$limit}";
                }

                if ($offset !== '') {
                    $sql .= " OFFSET {$offset}";
                }


                if (!$this->query($sql, $params)->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    // Custom select with joins, group by, order by
    public function select($columns, $table, $joins = [], $conditions = [], $groupBy = '', $orderBy = '', $limit = '', $offset = '')
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS {$columns} FROM {$table}";

        if (!empty($joins)) {
            foreach ($joins as $join) {
                $sql .= " {$join}";
            }
        }

        if (!empty($conditions)) {
            $operators = array("=", ">", "<", ">=", "<=", "<>", "!=", "LIKE", "IN", "NOT IN", "BETWEEN", "NOT BETWEEN");
            $sql .= " WHERE ";

            $conditionClauses = [];
            $params = [];

            foreach ($conditions as $field => $condition) {
                if (is_array($condition) && count($condition) === 2) {
                    list($operator, $value) = $condition;
                    if (in_array($operator, $operators)) {
                        $conditionClauses[] = "{$field} {$operator} ?";
                        $params[] = $value;
                    }
                }
            }

            $sql .= implode(' AND ', $conditionClauses);
        }

        if ($groupBy !== '') {
            $sql .= " GROUP BY {$groupBy}";
        }

        if ($orderBy !== '') {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit !== '') {
            $sql .= " LIMIT {$limit}";
        }

        if ($offset !== '') {
            $sql .= " OFFSET {$offset}";
        }

        //die($sql );

        if (!$this->query($sql, $params)->error()) {
            return $this;
        }

        return false;
    }

    // Get single record
    public function get_row($table, $conditions = array())
    {
        return $this->action('SELECT *', $table, $conditions, 1)->first();
    }

    // Count rows
    public function count_rows($table, $conditions = array()): int
    {
        $this->action('SELECT COUNT(*) as count', $table, $conditions);
        return $this->first()->count ?? 0;
    }

    // Sum
    public function sum($table, $column, $conditions = array())
    {
        $this->action("SELECT SUM({$column}) as sum", $table, $conditions);
        return $this->first()->sum ?? 0;
    }

    // Avg
    public function avg($table, $column, $conditions = array())
    {
        $this->action("SELECT AVG({$column}) as avg", $table, $conditions);
        return $this->first()->avg ?? 0;
    }

    // Min
    public function min($table, $column, $conditions = array())
    {
        $this->action("SELECT MIN({$column}) as min", $table, $conditions);
        return $this->first()->min ?? 0;
    }

    // Max
    public function max($table, $column, $conditions = array())
    {
        $this->action("SELECT MAX({$column}) as max", $table, $conditions);
        return $this->first()->max ?? 0;
    }


    // to delete
    /* 
   how to use
    $conditions = [
    'id' => ['=', 123],
    'status' => ['=', 'active'],
    'created_at' => ['<', '2024-05-19']
    ];

    $result = $db->delete('users', $conditions);
*/

    public function delete($table, $conditions)
    {
        return $this->action_delete('DELETE', $table, $conditions);
    }

    private function action_delete($action, $table, $conditions = array())
    {
        $operators = array("=", ">", "<", ">=", "<=", "<>", "!=", "LIKE", "IN", "NOT IN", "BETWEEN", "NOT BETWEEN");

        if (!empty($conditions) && is_array($conditions)) {
            $sql = "{$action} FROM {$table} WHERE ";

            $conditionClauses = [];
            $params = [];

            foreach ($conditions as $field => $condition) {
                if (is_array($condition) && count($condition) === 2) {
                    list($operator, $value) = $condition;
                    if (in_array($operator, $operators)) {
                        $conditionClauses[] = "{$field} {$operator} ?";
                        $params[] = $value;
                    }
                }
            }

            if (count($conditionClauses) > 0) {
                $sql .= implode(' AND ', $conditionClauses);

                if (!$this->query($sql, $params)->error()) {
                    return $this;
                }
            }
        }

        return false;
    }



    // to insert
    public function insert($table, $fields = array()): bool
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = implode(', ', array_fill(0, count($fields), '?'));

            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

            if (!$this->query($sql, array_values($fields))->error()) {
                $this->_last_insert_id = $this->_pdo->lastInsertId();
                return true;
            }
        }
        return false;
    }

    /*

// Fields to update
$fields = [
    'email' => 'newemail@example.com',
    'status' => 'inactive'
];

// Conditions to find the correct record
$conditions = [
    'id' => ['=', 123],
    'status' => ['=', 'active'],
    'created_at' => ['<', '2024-05-19']
];

// Call the update function
$success = $db->update($table, $fields, $conditions);

    */
    public function update($table, $fields = array(), $conditions = array()): bool
    {
        $operators = array("=", ">", "<", ">=", "<=", "<>", "!=", "LIKE", "IN", "NOT IN", "BETWEEN", "NOT BETWEEN");

        if (count($fields) && !empty($conditions) && is_array($conditions)) {
            $setClauses = [];
            $params = [];

            // Build the SET part of the query
            foreach ($fields as $field => $value) {
                $setClauses[] = "{$field} = ?";
                $params[] = $value;
            }

            // Build the WHERE part of the query
            $conditionClauses = [];
            foreach ($conditions as $field => $condition) {
                if (is_array($condition) && count($condition) === 2) {
                    list($operator, $value) = $condition;
                    if (in_array($operator, $operators)) {
                        $conditionClauses[] = "{$field} {$operator} ?";
                        $params[] = $value;
                    }
                }
            }

            if (count($setClauses) > 0 && count($conditionClauses) > 0) {
                $set = implode(', ', $setClauses);
                $conditions = implode(' AND ', $conditionClauses);
                $sql = "UPDATE {$table} SET {$set} WHERE {$conditions}";

                if (!$this->query($sql, $params)->error()) {
                    return true;
                }
            }
        }
        return false;
    }



    public function results()
    {
        return $this->_results;
    }

    public function first()
    {
        return $this->_results[0] ?? null;
    }

    public function count(): int
    {
        return $this->_count;
    }

    public function total_count(): int
    {
        return $this->_total_count;
    }

    public function error(): bool
    {
        return $this->_error;
    }
}
