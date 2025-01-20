<?php
require_once "../../vendor/autoload.php";

use Models\Course as Course;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $courseId = $data["courseId"] ?? null;
    if ($courseId === null) {
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }
    try {
        if (Course::deleteCourse($courseId)) {
            echo json_encode(["success" => "Course deleted successfully."]);
        } else {
            echo json_encode(["error" => "Failed to delete course."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
