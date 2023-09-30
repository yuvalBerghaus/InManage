<?php
class View {
    public static function DisplayPosts($result) {
        // Your PHP code for database operations and other logic here
    
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Your Page Title</title>
            <link rel="stylesheet" type="text/css" href="styles.css">
        </head>
        <body>
            <div class="posts">';
            
        if (count($result) > 0) {
            foreach ($result as $row) {
                $username = $row[UsersFields::USERNAME];
                $postTitle = $row[PostsFields::TITLE];
                $postContent = $row[PostsFields::BODY];
                $createdAt = $row[PostsFields::CREATED_AT];
                
                // Retrieve and display user image
                $userImage = 'images/65118075a1396.jpg'; // Update with the correct image path
                
                // Generate HTML for a post with improved styling
                echo '<div class="post">';
                echo '<div class="post-header">';
                echo '<img class="user-image" src="' . $userImage . '" alt="User Image">';
                echo '<div class="post-info">';
                echo '<span class="username">' . $username . '</span>';
                echo '<span class="timestamp">Posted on: ' . $createdAt . '</span>';
                echo '</div>';
                echo '</div>';
                echo '<h2 class="post-title">' . $postTitle . '</h2>';
                echo '<p class="post-content">' . $postContent . '</p>';
                echo '</div>';
            }
        } else {
            echo 'No posts found.';
        }
    
        echo '
            </div>
        </body>
        </html>';
    } 

    public static function DisplayLastPost($result) {
        // Your PHP code for database operations and other logic here
        echo $result;
    } 
}
?>
