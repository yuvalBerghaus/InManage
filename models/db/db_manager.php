<?php
require_once './models/enums/enums.php';
require_once './includes/config.php'; // Include the configuration file
// A SingleTon DataBase object since we do not need more than one object to represent our DB
class DataBase {
    private static $instance;
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    // Public method to get or create the singleton instance
    public static function GetInstance() {
        if (!self::$instance) {
            self::$instance = new self(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        }
        return self::$instance;
    }
    
    // Private constructor to prevent external instantiation
    private function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        // Create a database connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
/**
 * Creates a new database table.
 *
 * This method generates and executes an SQL query to create a new table in the database.
 * It allows you to specify the table name, columns, and additional query options.
 *
 * @param string $table_name The name of the table to be created.
 * @param string|array $columns The table columns as a comma-separated string or an array.
 * @param string $query Additional query options (e.g., primary key, constraints).
 *
 * @throws Exception if an error occurs while executing the query.
 *
 * @return bool Returns true if the table is created successfully; otherwise, an exception is thrown.
 */
    public function Create($table_name, $columns, $query) {
        $columns = is_array($columns) ? implode(", ", $columns) : $columns;
        if($query != null) {
            $query = "CREATE TABLE $table_name($columns, $query)";
        }
        else {
            $query = "CREATE TABLE $table_name($columns)";
        }
        $result = $this->conn->query($query);
        if (!$result) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }
        return true;
    }

    /**
     * Executes a SELECT query on the specified table with optional JOIN operation.
     *
     * @param string $table The name of the table to select data from.
     * @param mixed $columns Optional. The columns to retrieve. Default is "*".
     *                      You can provide an array of column names or a string with comma-separated column names.
     * @param string $condition Optional. The condition to filter rows (e.g., "column = 'value'").
     * @param string $orderByField Optional. The field to order the results by.
     * @param string $orderBy Optional. The order direction, either "ASC" (ascending) or "DESC" (descending).
     * @param int $limit Optional. The maximum number of rows to retrieve.
     * @param string|null $joinTable Optional. The name of the table to join with.
     * @param string|null $joinCondition Optional. The condition for the JOIN operation (e.g., "table1.column = table2.column").
     *
     * @return array An array containing the selected rows from the database.
     * @throws Exception If an error occurs during query execution.
     */
    public function Select($table, $columns = "*", $condition, $orderByField, $orderBy, $limit, $joinTable = null, $joinCondition = null) {
        $columns = is_array($columns) ? implode(", ", $columns) : $columns;
        $query = "SELECT $columns FROM $table";
    
        // Add the JOIN clause if both joinTable and joinCondition are provided
        if (!empty($joinTable) && !empty($joinCondition)) {
            $query .= " JOIN $joinTable ON $joinCondition";
        }
    
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        if (!empty($orderByField)) {
            $query .= " ORDER BY $orderByField $orderBy";
        }
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }
    
        $result = $this->conn->query($query);
    
        if ($result === false) {
            die("Error executing query: " . $this->conn->error);
        }
    
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    
        return $rows;
    }
    
    /**
     * Insert data into a database table.
     *
     * This function inserts data into the specified database table using the provided data array.
     *
     * @param string $table The name of the database table to insert data into.
     * @param array $data An associative array where keys represent column names, and values represent the data to be inserted.
     *
     * @throws Exception If there is an error during the insertion process, an exception is thrown with a relevant error message.
     *
     * @return void This function does not return a value.
     */
    public function Insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        if ($this->conn->query($query) === false) {
            die("Error executing query: " . $this->conn->error);
        }
    }
    /**
     * Delete records from a database table based on a condition.
     *
     * This function deletes records from the specified database table that match the provided condition.
     *
     * @param string $table The name of the database table from which to delete records.
     * @param string $condition The condition used to determine which records to delete.
     *
     * @throws Exception If there is an error during the deletion process, an exception is thrown with a relevant error message.
     *
     * @return void This function does not return a value.
     */
    public function Delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";

        if ($this->conn->query($query) === false) {
            die("Error executing query: " . $this->conn->error);
        }
    }
    /**
     * Update records in a database table based on a condition.
     *
     * This function updates records in the specified database table using the provided data and condition.
     *
     * @param string $table The name of the database table in which to update records.
     * @param array $data An associative array where keys represent column names, and values represent the updated data.
     * @param string $condition The condition used to determine which records to update.
     *
     * @throws Exception If there is an error during the update process, an exception is thrown with a relevant error message.
     *
     * @return void This function does not return a value.
     */
    public function Update($table, $data, $condition) {
        $setValues = [];
        foreach ($data as $column => $value) {
            $setValues[] = "$column = '$value'";
        }

        $setClause = implode(", ", $setValues);
        $query = "UPDATE $table SET $setClause WHERE $condition";

        if ($this->conn->query($query) === false) {
            die("Error executing query: " . $this->conn->error);
        }
    }
    /**
     * Perform a one-to-many SQL join and retrieve data from multiple database tables.
     *
     * This function performs an SQL join between two specified database tables and retrieves data based on the provided columns. It is typically used for one-to-many relationships where data from one table is joined with data from another table.
     *
     * @param string $table_1 The name of the first database table involved in the join.
     * @param string $table_2 The name of the second database table involved in the join.
     * @param string $key The name of the key column in the first table used for joining.
     * @param string $foreign_key The name of the foreign key column in the second table used for joining.
     * @param string|array $columns Optional. The columns to retrieve from the joined tables. You can provide either a single string or an array of strings.
     *
     * @throws Exception If there is an error during the SQL query execution, an exception is thrown with a relevant error message.
     *
     * @return array An array of associative arrays representing the retrieved data from the joined tables.
     */
    public function JoinOneToMany($table_1, $table_2, $key, $foreign_key, $columns = "*") {
        // Prepare the columns for the query
        $columns = is_array($columns) ? implode(", ", $columns) : $columns;
    
        // Your SQL query to retrieve data
        $query = "SELECT $columns
                  FROM $table_1
                  JOIN $table_2 ON $table_1.$key = $table_2.$foreign_key";
        $result = $this->conn->query($query);
    
        if (!$result) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }
    
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    
        return $rows;
    }
    /**
     * Initialize the Auto Increment value for a database table.
     *
     * This function resets the Auto Increment value for the specified database table to 1, effectively restarting the numbering for primary key values.
     *
     * @param string $table The name of the database table for which to initialize the Auto Increment value.
     *
     * @throws Exception If there is an error during the SQL query execution, an exception is thrown with a relevant error message.
     *
     * @return bool Returns true if the Auto Increment initialization is successful, otherwise false.
     */
    public function InitAI($table) {
        $query = "ALTER TABLE $table AUTO_INCREMENT = 1";
        $result = $this->conn->query($query);
        if (!$result) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }
        return true;
    }
    
    public function __destruct() {
        // Close the database connection when the object is destroyed
        $this->conn->close();
    }
}



?>
