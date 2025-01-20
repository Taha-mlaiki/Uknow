<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Category;
use Classes\Category as CategoryClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $categoryId = $data['id'] ?? null;

    if($categoryId === null){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }
    try {
        $catObject = new CategoryClass($categoryId, "");
        $category = new Category();
        if($category->deleteCategory($catObject)){
            echo json_encode(["message" => "Category deleted successfully."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
