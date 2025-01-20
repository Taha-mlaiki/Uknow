<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";
use Models\Category;

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    try {
        $categories = new Category();
        $categories = $categories->getCategories();
        $serializedCategories = [];
        foreach ($categories as $category) {
            $serializedCategories[] = ["id"=> $category->getId(), "name" => $category->getName()];
        }
        echo json_encode(["categories" => $serializedCategories]);
    } catch (\Throwable $th) {  
        echo json_encode(["error" => $th->getMessage()]);
    }
}
