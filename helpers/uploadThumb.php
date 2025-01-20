<?php

function uploadThumb($files)
{

    $uploadDir = '../../assets/uploads/';
    try {
        if (isset($files['thumbnail']) && $files['thumbnail']['error'] == 0) {
            $fileTmpPath = $files['thumbnail']['tmp_name'];
            $fileName = $files['thumbnail']['name'];

            // Extract file extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Define allowed file types
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            // Check if the file extension is allowed
            if (in_array($fileExtension, $allowedExtensions)) {
                // Generate a unique name for the file (to avoid overwriting)
                $newFileNameThumb = uniqid('thumb_', true) . '.' . $fileExtension;


                // Define the destination path
                $destinationPath = $uploadDir . $newFileNameThumb;

                if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                    return $newFileNameThumb;
                } else {
                    throw new Error("Error moving the file to the destination folder.");
                }
            } else {
                throw new Error("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }
        }
    } catch (\Throwable $th) {
        throw new Error($th->getMessage());
    }
}
