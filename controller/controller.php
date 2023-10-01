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

function Task_3() {
    if(DataBaseOperations::InitAI()) {
        $users = ApiHandler::GetDataFromAPI('https://jsonplaceholder.typicode.com/users');
        $posts = ApiHandler::GetDataFromAPI('https://jsonplaceholder.typicode.com/posts');
        DataBaseOperations::InsertDataIntoDatabase($users, UsersFields::TABLE_NAME);
        DataBaseOperations::InsertDataIntoDatabase($posts, PostsFields::TABLE_NAME);
        echo "Task 3 Succeeded!";
    }
    else
        echo "Unable to do task 3";
}

function Task_4() {
    ImageHandler::SaveImageFromURL('https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg');
}

function Task_5() {
    View::DisplayPosts('listView', DataBaseOperations::GetPosts());
}

function Task_6() {
    View::DisplayPosts('last_post', DataBaseOperations::GetLatestUserPostOfMonth());
}

function Task_7() {
    DataBaseOperations::CreateTablePostsPerHour();
}

?>