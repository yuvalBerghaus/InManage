<?php
require_once './models/enums.php';
require_once __DIR__ . '/db_manager.php';


class DataBaseOperations {
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
                } else {
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

    public static function GetPosts() {
        $db = DataBase::GetInstance();
        $result = $db->JoinOneToMany(UsersFields::TABLE_NAME, PostsFields::TABLE_NAME, UsersFields::ID, PostsFields::USERID, "*");
        if ($result && is_array($result)) {
            return $result;
        } else {
            return []; // Return an empty array or handle the error as needed
        }
    }

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

    public static function GetLatestUserPostOfMonth() {
        $db = DataBase::GetInstance();
        $table = "users";
        $columns = "*";
        $condition = "MONTH(" . UsersFields::BIRTH_DATE . ") = MONTH(CURRENT_DATE())";
        $orderByField = "DAY(" . PostsFields::CREATED_AT . ")";
        $orderBy = "DESC";
        $limit = 1;
    
        $result = $db->Select($table, $columns, $condition, $orderByField, $orderBy, $limit);
    
        // Check if any results were returned
        if (count($result) > 0) {
            // Return the first result as the latest user post of the month
            return $result[0];
        } else {
            // No posts found
            return null;
        }
    }
    
    public static function InitAI() {
        $db = DataBase::GetInstance();
        return $db->InitAI('users');
    }
    

}

?>