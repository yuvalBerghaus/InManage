<?php
require_once './models/enums.php';
require_once './models/api_handler.php';
require_once './view.php';
require_once './models/image_handler.php';
require_once './models/db/operations.php';

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
    View::DisplayPosts(DataBaseOperations::GetPosts());
}

function Task_6() {
    View::DisplayLastPost(DataBaseOperations::GetLatestUserPostOfMonth());
}

function Task_7() {
    DataBaseOperations::CreateTablePostsPerHour();
}

/*
7)
-- Create a new table with a primary key or unique constraint
CREATE TABLE IF NOT EXISTS posts_per_hour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE,
    hour INT,
    post_count INT,
    UNIQUE KEY unique_date_hour (date, hour)
);


INSERT INTO posts_per_hour(
    date,
    hour,
    amount
)
SELECT
    DATE(created_at) AS date,
    HOUR(created_at) AS hour,
    COUNT(*) AS post_count
FROM
    posts
GROUP BY
    DATE(created_at),
    HOUR(created_at)
ORDER BY
    date,
    hour
ON DUPLICATE KEY UPDATE post_count = VALUES(post_count);


SELECT *
FROM users
JOIN posts ON users.id = posts.userId
WHERE MONTH(users.birth_date) = MONTH(CURRENT_DATE())
ORDER BY DAY(posts.created_at) DESC
LIMIT 1;


*/



?>