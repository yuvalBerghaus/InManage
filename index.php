<?php
require './controller/controller.php';
require './includes/config.php';

$db = DataBase::getInstance('localhost', 'root', '', 'inmanage');
function displayPosts() {
    global $db;
    $result = GetPosts($db);

    if (count($result) > 0) {
        foreach ($result as $row) {
            $username = $row['username'];
            $postTitle = $row['title'];
            $postContent = $row['body'];
            $createdAt = $row['created_at'];
            
            // Retrieve and display user image
            $userImage = 'images/65118075a1396.jpg'; // Update with the correct image path
            
            // Generate HTML for a post
            echo '<div class="post">';
            echo '<div class="post-header">';
            echo '<img class="user-image" src="' . $userImage . '" alt="User Image">';
            echo '<div class="post-info">';
            echo '<h3>' . $username . '</h3>';
            echo '<p class="timestamp">Posted on: ' . $createdAt . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<h4>' . $postTitle . '</h4>';
            echo '<p>' . $postContent . '</p>';
            echo '</div>';
        }
    } else {
        echo 'No posts found.';
    }
}





// Example usage
// $imageUrl = 'https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';
// SaveImageFromURL($imageUrl);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Page Title</title>
    
    <!-- Include the external CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- Your HTML content here -->
    <div class="post">
        <?php displayPosts(); ?>
    </div>
</body>
</html>

