<?php
require './controller/controller.php';
require './includes/config.php';
// Example usage
// $imageUrl = 'https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg';
// SaveImageFromURL($imageUrl);
$db = new DataBase('localhost', 'root', '', 'inmanage');
$posts_data = GetDataFromAPI('https://jsonplaceholder.typicode.com/users');
InsertUsersIntoDatabase($db, $posts_data);
?>
