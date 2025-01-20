<?php


function uploadVideo($files)
{
    $uploadDir = '../../assets/uploads/';
    try {
        if (isset($files['video']) && $files['video']['error'] == 0) {
            $fileTmpPath = $files['video']['tmp_name'];
            $fileName = $files['video']['name'];

            // Extract file extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Define allowed file types
            $allowedExtensions = ['mp4', 'avi', 'mov', 'mkv'];;

            // Check if the file extension is allowed
            if (in_array($fileExtension, $allowedExtensions)) {
                // Generate a unique name for the file (to avoid overwriting)
                $newVideoFileName = uniqid('video_', true) . '.' . $fileExtension;

                // Define the destination path
                $destinationPath = $uploadDir . $newVideoFileName;

                if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                    return $newVideoFileName;
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
