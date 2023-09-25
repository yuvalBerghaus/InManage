<?php
class DataBase {
    private static $instance;
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    // Public method to get or create the singleton instance
    public static function getInstance($host, $username, $password, $database) {
        if (!self::$instance) {
            self::$instance = new self($host, $username, $password, $database);
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

    public function Select($table, $columns = "*", $condition = "") {
        $query = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
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

    public function __destruct() {
        // Close the database connection when the object is destroyed
        $this->conn->close();
    }
}

?>
