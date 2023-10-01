<?php
require_once './models/enums/enums.php';
require_once __DIR__ . '/db_manager.php';


class DBOperations {
    /**
     * Insert data into a database table.
     *
     * This function inserts data obtained from an API instance into a specified database table.
     *
     * @param array $data The data to be inserted, typically originating from the API.
     * @param string $table The name of the database table to insert data into.
     *
     * @throws Exception If an invalid table name is specified, an exception is thrown.
     *
     * @return void This function does not return a value.
     */

    public static function InsertDataIntoDatabase($data, $table) {
        // $data originates from the API instance
        // ENUMS originates from the database fields
        $db = DataBase::GetInstance();
        try{
            foreach ($data as $item) {
                // Determine the table name and data based on the insertType parameter
                // case table_name is users
                if ($table === UsersFields::TABLE_NAME) {
                    $tableName = UsersFields::TABLE_NAME;
                    $insertData = array(
                        UsersFields::USERNAME => $item['username'],
                        UsersFields::EMAIL => $item['email'],
                        UsersFields::ACTIVE => true
                    );
                // case table_name is Posts
                } elseif ($table === PostsFields::TABLE_NAME) {
                    $tableName = PostsFields::TABLE_NAME;
                    $insertData = array(
                        PostsFields::USERID => $item['userId'],
                        PostsFields::TITLE => $item['title'],
                        PostsFields::BODY => $item['body'],
                        PostsFields::ACTIVE => true
                    );
                } 
                else if($table === PostsPerHourFields::TABLE_NAME) {
                    
                }
                else {
                     // Handle invalid table_name by throwing an exception
                     throw new Exception("Invalid table_name specified.");
                }
        
                // Insert the data into the specified table
                $db->Insert($tableName, $insertData);
            }
        }
        catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    /**
     * Retrieve and return posts data with user information.
     *
     * This function performs a database query to retrieve posts data along with associated user information by performing a JOIN operation between the "users" and "posts" tables.
     *
     * @return array An array of posts data with associated user information. Returns an empty array if no data is found or an error occurs during execution.
     */
    public static function GetPosts() {
        $db = DataBase::GetInstance();
        $result = $db->JoinOneToMany(UsersFields::TABLE_NAME, PostsFields::TABLE_NAME, UsersFields::ID, PostsFields::USERID, "*");
        if ($result && is_array($result)) {
            return $result;
        } else {
            return []; // Return an empty array or handle the error as needed
        }
    }

    /**
     * Create a new table to store posts per hour data.
     *
     * This function creates a new database table called "posts_per_hour" with specific columns for tracking the number of posts per hour.
     *
     * @throws Exception If there is an error during the table creation process, an exception is thrown.
     *
     * @return void This function does not return a value.
     */
    public static function CreateTablePostsPerHour() {
        // CREATE TABLE
        $db = DataBase::GetInstance();
        $columns = [
            PostsPerHourFields::ID . ' INT AUTO_INCREMENT PRIMARY KEY',
            PostsPerHourFields::DATE . ' DATE',
            PostsPerHourFields::HOUR . ' INT',
            PostsPerHourFields::POSTS_PER_HOUR . ' INT'
        ];
        $query = "UNIQUE KEY unique_date_hour (" . PostsPerHourFields::DATE . ", " . PostsPerHourFields::HOUR . ")";
        $db->Create(PostsPerHourFields::TABLE_NAME, $columns, $query);
    }

    public static function CreateTable($tableName) {
        $db = DataBase::GetInstance();
        $columns = null;
        $query = null;
        try {
            if($tableName === PostsFields::TABLE_NAME) {
                $columns = [
                    PostsFields::ID . ' INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
                    PostsFields::ACTIVE . ' tinyint(1) NOT NULL',
                    PostsFields::USERID . ' int(11) NOT NULL',
                    PostsFields::TITLE . ' varchar(50) NOT NULL',
                    PostsFields::BODY . ' varchar(220) NOT NULL',
                    PostsFields::CREATED_AT . ' datetime NOT NULL DEFAULT current_timestamp()'
                ];
            }
            else if($tableName == UsersFields::TABLE_NAME) {
                $columns = [
                    UsersFields::ID . ' INT AUTO_INCREMENT PRIMARY KEY',
                    UsersFields::EMAIL . ' VARCHAR(100) NOT NULL',
                    UsersFields::ACTIVE . ' tinyint(1) NOT NULL',
                    UsersFields::USERNAME . ' VARCHAR(64) NOT NULL',
                    UsersFields::BIRTH_DATE . ' DATE NOT NULL DEFAULT CURRENT_TIMESTAMP()',
                ];
            }
            else if($tableName === PostsPerHourFields::TABLE_NAME) {
                $columns = [
                    PostsPerHourFields::ID . ' INT AUTO_INCREMENT PRIMARY KEY',
                    PostsPerHourFields::DATE . ' DATE',
                    PostsPerHourFields::HOUR . ' INT',
                    PostsPerHourFields::POSTS_PER_HOUR . ' INT'
                ];
                $query = "UNIQUE KEY unique_date_hour (" . PostsPerHourFields::DATE . ", " . PostsPerHourFields::HOUR . ")";

            }
            else {
                throw new Exception("Table name does not correspond to the task!");
            }
            $db->Create($tableName, $columns, $query);
        }
        catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    /**
     * Retrieve the latest user post of the current month.
     *
     * This function queries the database to retrieve the latest user post of the current month based on the user's birth date and post creation date.
     *
     * @return array|null An associative array representing the latest user post of the month, or null if no posts are found or an error occurs during execution.
     */
    public static function GetLatestUserPostOfMonth() {
        $db = DataBase::GetInstance();
        $tableUsers = UsersFields::TABLE_NAME;
        $tablePosts = PostsFields::TABLE_NAME;
        $columns = "*";
        $condition = "MONTH(`" . $tableUsers . "`.`" . UsersFields::BIRTH_DATE . "`) = MONTH(CURRENT_DATE())";
        $orderByField = "DAY(`" . $tablePosts . "`.`" . PostsFields::CREATED_AT . "`)";
        $orderBy = "DESC";
        $limit = 1;
        
        // Specify the join table and condition
        $joinTable = $tablePosts;
        $joinCondition = "`" . $tableUsers . "`.`" . UsersFields::ID . "` = `" . $tablePosts . "`.`" . PostsFields::USERID . "`";
    
        $result = $db->Select($tableUsers, $columns, $condition, $orderByField, $orderBy, $limit, $joinTable, $joinCondition);
        
        // Check if any results were returned
        if (count($result) > 0) {
            // Return the first result as the latest user post of the month
            return $result[0];
        } else {
            echo "no posts";
            // No posts found
            return null;
        }
    }
    
    
    

    public static function InitAI() {
        $db = DataBase::GetInstance();
        return $db->InitAI(UsersFields::TABLE_NAME);
    }
    

}

?>