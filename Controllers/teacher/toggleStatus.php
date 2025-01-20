<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Teacher;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $teacherId = $data['id'] ?? null;
    $status = $data['status'] ?? false;

    if($teacherId === null){
        echo json_encode(["error" => "Id is required."]);
        exit();
    }

    try {
        $teacherObject = new Teacher();
        if($teacherObject->updateStatus($teacherId,$status ? 1:0)){
            echo json_encode(["message" => "Status updated successfully."]);
            exit();
        }else {
            echo json_encode(["error" => "teacher not found."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
