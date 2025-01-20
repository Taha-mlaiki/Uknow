<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Category;
use Classes\Category as CategoryClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $Categories = $data['categories'] ?? null;
    $categoryId = $data['id'] ?? null;

    if($Categories === null || empty($Categories)|| $categoryId === null){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    if(count($Categories) > 1){
        echo json_encode(["error" => "Only one category can be updated at a time."]);
        exit();
    }

    try {
        $catObject = new CategoryClass($categoryId,$Categories[0]);
        $updateCategory = new Category();
        if($updateCategory->updateCategory($catObject)){
            echo json_encode(["message" => "Category updated successfully."]);
            exit();
        }else {
            echo json_encode(["error" => "Category not found."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
