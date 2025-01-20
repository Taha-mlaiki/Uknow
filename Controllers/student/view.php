<?php
use Models\Student;
header("Content-Type: application/json");
require_once "../../vendor/autoload.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $student = new Student();
        $students = $student->getStudents();
        $serializedtudents = [];

        foreach ($students as $student) {
            $serializedtudents[] = [
                "id" => $student->getId(),
                "username" => $student->getUsername(),
                "email" => $student->getEmail(),
                "isActive" => $student->getIsActive()
            ];
        }
        echo json_encode(["students" => $serializedtudents]);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
