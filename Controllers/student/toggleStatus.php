<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Student;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $studentId = $data['id'] ?? null;
    $status = $data['status'] ?? false;

    if($studentId === null){
        echo json_encode(["error" => "Id is required."]);
        exit();
    }

     
    try {
        $studentObject = new Student();
        if($studentObject->updateStatus($studentId,$status ? 1:0)){
            echo json_encode(["message" => "Status updated successfully."]);
            exit();
        }else {
            echo json_encode(["error" => "student not found."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
