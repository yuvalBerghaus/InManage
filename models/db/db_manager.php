<?php
require_once './models/enums.php';
require_once './config.php'; // Include the configuration file
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

    public function Create($table_name, $columns, $query) {
        $columns = is_array($columns) ? implode(", ", $columns) : $columns;
        $query = "CREATE TABLE $table_name($columns, $query)";
        $result = $this->conn->query($query);
        if (!$result) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }
        return true;
    }

    public function Select($table, $columns = "*", $condition, $orderByField, $orderBy, $limit) {
        $query = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        if (!empty($orderByField)) {
            $query .= " ORDER BY $orderByField " . $orderBy;
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

    public function Insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        if ($this->conn->query($query) === false) {
            die("Error executing query: " . $this->conn->error);
        }
    }

    public function Delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";

        if ($this->conn->query($query) === false) {
            die("Error executing query: " . $this->conn->error);
        }
    }

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
