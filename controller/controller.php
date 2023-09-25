<?php
// Function to insert data into the database
function InsertPostsIntoDatabase($db, $tb_name, $data) {
    foreach ($data as $post) {
        // Prepare data for insertion
        $postData = array(
            'userId' => $post['userId'],
            'title' => $post['title'],
            'body' => $post['body'],
            'active' => true
        );
        
        // Insert the user into the 'users' table
        $db->Insert('posts', $postData);
    }
}

// Function to insert users into the DB *corresponding* to the fields DB~JSONPlaceholder
function InsertUsersIntoDatabase($db, $data) {
    foreach ($data as $user) {
        // Prepare data for insertion
        $userData = array(
            'username' => $user['username'],
            'email' => $user['email'],
            'active' => true
        );
        
        // Insert the user into the 'users' table
        $db->Insert('users', $userData);
    }
}


// Function to fetch data from the API
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




?>