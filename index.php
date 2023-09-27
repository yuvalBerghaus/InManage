<?php
require './controller/controller.php';
require './models/db.php';


// Example usage
// $imageUrl = 'https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';
// SaveImageFromURL($imageUrl);


//Example inserting data into database
// $api = 'https://jsonplaceholder.typicode.com/posts';
// $data = GetDataFromAPI($api);
// InsertDataIntoDatabase($data, 'posts');
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
    <div class="posts">
        <?php DisplayPosts(); ?>
    </div>
</body>
</html>

