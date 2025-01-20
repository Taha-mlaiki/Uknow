<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Tag;
use Classes\Tag as TagClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $tagId = $data['id'] ?? null;

    if($tagId === null){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }
    try {
        $tagObject = new TagClass($tagId, "");
        $tag = new Tag();
        if($tag->deleteTag($tagObject)){
            echo json_encode(["message" => "Category deleted successfully."]);
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
