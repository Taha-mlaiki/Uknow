<?php
require_once "../../vendor/autoload.php";

use Models\Enrollment;

header('Content-Type: application/json');


try {
    $data = json_decode(file_get_contents("php://input"), true);

    $userId = isset($data['userId']) ? (int)$data['userId'] : null;
    if ($userId) {
        $courses = Enrollment::getAllCourseEnrolledByUserId($userId);
        echo json_encode(["courses" => $courses]);
        exit();
    }
    throw new Error("user id not exist");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred: ' . $e->getMessage()
    ]);
}
