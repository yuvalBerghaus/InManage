<?php
class View {
    public static function DisplayPosts($displayType, $result) {
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
            
        if (count($result) > 0)
            if($displayType == DisplayMethodTypes::LIST_VIEW)
                foreach ($result as $row)
                    self::DisplayPost($row);
            else if($displayType == DisplayMethodTypes::LAST_POST)
                self::DisplayPost($result);
        else
            echo 'No posts found.';
    
        echo '
            </div>
        </body>
        </html>';
    } 
    public static function DisplayPost($result) {
        $username = $result[UsersFields::USERNAME];
        $postTitle = $result[PostsFields::TITLE];
        $postContent = $result[PostsFields::BODY];
        $createdAt = $result[PostsFields::CREATED_AT];
        
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
    
}
?>
