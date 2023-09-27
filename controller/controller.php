<?php
require_once './models/enums.php';


function InsertDataIntoDatabase($data, $table) {
    // $data originates from the API instance
    // ENUMS originates from the database fields
    $db = DataBase::getInstance();
    foreach ($data as $item) {
        // Determine the table name and data based on the insertType parameter
        if ($table === UsersFields::TABLE_NAME) {
            $tableName = UsersFields::TABLE_NAME;
            $insertData = array(
                UsersFields::USERNAME => $item['username'],
                UsersFields::EMAIL => $item['email'],
                UsersFields::ACTIVE => true
            );
        } elseif ($table === PostsFields::TABLE_NAME) {
            $tableName = PostsFields::TABLE_NAME;
            $insertData = array(
                PostsFields::USERID => $item['userId'],
                PostsFields::TITLE => $item['title'],
                PostsFields::BODY => $item['body'],
                PostsFields::ACTIVE => true
            );
        } else {
            // Handle invalid insertType
            echo "Invalid insertType specified.";
            return;
        }

        // Insert the data into the specified table
        $db->Insert($tableName, $insertData);
    }
}



function GetDataFromAPI($apiUrl) {
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        die("cURL Error: " . curl_error($curl));
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (empty($data)) {
        die("Failed to fetch data from the API.");
    }

    return $data;
}



function SaveImageFromURL($imageUrl) {
    // Get the image data
    $imageData = file_get_contents($imageUrl);

    if ($imageData === false) {
        die('Failed to fetch the image.');
    }

    // Determine the file extension based on the URL
    $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);

    // Generate a unique filename for the image
    $filename = uniqid() . '.' . $extension;

    // Specify the predetermined save path
    $savePath = 'images';

    // Construct the full local path
    $localPath = $savePath . '/' . $filename;

    // Save the image to the local directory
    if (file_put_contents($localPath, $imageData) !== false) {
        echo 'Image saved as ' . $localPath;
    } else {
        die('Failed to save the image.');
    }
}


/* 

SELECT *
FROM users
JOIN posts ON users.id = posts.userId;

*/

function GetPosts() {
    $db = DataBase::getInstance();
    $result = $db->JoinOneToMany(UsersFields::TABLE_NAME, PostsFields::TABLE_NAME, UsersFields::ID, PostsFields::USERID, "*");
    if ($result && is_array($result)) {
        return $result;
    } else {
        return []; // Return an empty array or handle the error as needed
    }
}


function DisplayPosts() {
    $result = GetPosts();
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
}




?>