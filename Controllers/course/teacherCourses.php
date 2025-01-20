<?php
header("Content-Type: application/json");
session_start();
require_once "../../vendor/autoload.php";

use  Models\Course as Course;

try {
    $userId = $_SESSION['user']['id'];
    $courses = Course::getCoursesByUserId($userId);

    $formattedCourses = [];
    foreach ($courses as $course) {
        $formattedCourses[] = $course->toArray();
    }
    echo json_encode(["courses" => $formattedCourses]);
} catch (\Throwable $th) {
    echo json_encode(["error" => $th->getMessage()]);
}
