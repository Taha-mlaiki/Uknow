<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Tag;
use Classes\Tag as TagClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $tags = $data['tags'] ?? null;
    $tagId = $data['id'] ?? null;

    if($tags === null || empty($tags)|| $tagId === null){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    if(count($tags) > 1){
        echo json_encode(["error" => "Only one tag can be updated at a time."]);
        exit();
    }

    try {
        $tagObject = new TagClass($tagId,$tags[0]);
        $updateCategory = new Tag();
        if($updateCategory->updateTag($tagObject)){
            echo json_encode(["message" => "Tag updated successfully."]);
            exit();
        }else {
            echo json_encode(["error" => "Tag not found."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
