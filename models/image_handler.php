<?php
class ImageHandler {
    public static function SaveImageFromURL($imageUrl) {
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
}

?>