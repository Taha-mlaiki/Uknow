<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";

use Models\Tag;
use Classes\Tag as TagClass;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $tags = $data['tags'] ?? null;
    if($tags === null || empty($tags)){
        echo json_encode(["error" => "All fields are required."]);
        exit();
    }

    $failedTags = 0;
    $successTags = 0;
    try {
        foreach($tags as $tag){
            $TagObj = new TagClass(0,$tag);
            $newTag = new Tag();
            if($newTag->checkExistence($TagObj)){
                $failedTags++;
            }else {
                if($newTag->createTag($TagObj)){
                    $successTags++;
                }
            }
        }
        echo json_encode(["message" => "Tags created successfully.", "failed" => $failedTags, "success" => $successTags]);
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
}
