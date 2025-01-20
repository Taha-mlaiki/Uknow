<?php
use Models\Teacher;
header("Content-Type: application/json");
require_once "../../vendor/autoload.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $teacher = new Teacher();
        $teachers = $teacher->getTeachers();
        $serializedTeachers = [];
        foreach ($teachers as $teacher) {
            $serializedTeachers[] = [
                "id" => $teacher->getId(),
                "username" => $teacher->getUsername(),
                "email" => $teacher->getEmail(),
                "isActive" => $teacher->getIsActive()
            ];
        }
        echo json_encode(["teachers" => $serializedTeachers]);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
