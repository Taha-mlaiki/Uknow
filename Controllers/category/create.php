<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Category;
use Classes\Category as CategoryClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $Categories = $data['categories'] ?? null;
    if($Categories === null || empty($Categories)){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $failedCategories = 0;
    $successCategories = 0;
    try {
        foreach($Categories as $category){
            $catObj = new CategoryClass(0,$category);
            $newCategory = new Category();
            if($newCategory->checkExistence($catObj)){
                $failedCategories++;
            }else {
                if($newCategory->createCategory($catObj)){
                    $successCategories++;
                }
            }
        }
        echo json_encode(["message" => "Categories created successfully.", "failed" => $failedCategories, "success" => $successCategories]);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
