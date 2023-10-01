<?php
require_once './models/enums/enums.php';
require_once './models/api/api_handler.php';
require_once './view/view.php';
require_once './models/image/image_handler.php';
require_once './models/db/operations.php';

/*
 Task_1 DataBase class is written in ./models/db/db_manager
*/

/**
 * Task_2 Used the jsonPlaceHolder for my database
*/



/**
 * Task_3 Function
 * Performs the following tasks:
 * 1. Creates Users and Posts tables.
 * 2. Initializes auto-increment for the Users table.
 * 3. Fetches data from the specified API endpoints.
 * 4. Inserts fetched data into the Users and Posts tables.
 *
 * @return void
 */
function CreateTablesAndInsertApiTask() {
    try {
        // Step 1: Create Users and Posts tables.
        DBOperations::CreateTable(UsersFields::TABLE_NAME);
        DBOperations::CreateTable(PostsFields::TABLE_NAME);

        // Step 2: Initialize auto-increment for the Users table.
        if (DBOperations::InitAI(UsersFields::TABLE_NAME)) {
            // Step 3: Fetch data from API endpoints
            $users = ApiHandler::GetDataFromAPI('https://jsonplaceholder.typicode.com/users');
            $posts = ApiHandler::GetDataFromAPI('https://jsonplaceholder.typicode.com/posts');

            // Step 4: Insert fetched data into the Users and Posts tables.
            DBOperations::InsertDataIntoDatabase($users, UsersFields::TABLE_NAME);
            DBOperations::InsertDataIntoDatabase($posts, PostsFields::TABLE_NAME);
            echo "Task 3 Succeeded!";
        }
    } catch (Exception $e) {
        echo "Error in CreateTablesAndInsertApiTask: " . $e->getMessage();
    }
}

/**
 * Task_4 Function
 * Performs the following task:
 * Downloads and saves an image from a specified URL using ImageHandler.
 *
 * @return void
 */
function SaveImageFromURLTask() {
    ImageHandler::SaveImageFromURL('https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg');
}

/**
 * Displays a list of posts using View class and data from DBOperations::GetPosts().
 */
function DisplayPostsTask() {
    View::DisplayPosts(DisplayMethodTypes::LIST_VIEW, DBOperations::GetPosts());
}

/**
 * Displays the latest user post of the month that was born in the current month using View class and data from DBOperations::GetLatestUserPostOfMonth().
 */
function DisplayLatestPostTask() {
    View::DisplayPosts(DisplayMethodTypes::LAST_POST, DBOperations::GetLatestUserPostOfMonth());
}


function CreateTablePostsPerHourTask() {
    DBOperations::CreateTable(PostsPerHourFields::TABLE_NAME);
    echo "Table PostsPerHour created successfully!";
}

?>