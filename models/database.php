class DataBase {
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($host, $username, $password, $database) {
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
