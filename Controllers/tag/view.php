<?php

header("Content-Type: application/json");
require_once "../../vendor/autoload.php";
use Models\Tag;

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    try {
        $tagsObject = new Tag();
        $tags = $tagsObject->getTags();
        $serializedTags = [];
        foreach ($tags as $tag) {
            $serializedTags[] = ["id"=> $tag->getId(), "name" => $tag->getName()];
        }
        echo json_encode(["tags" => $serializedTags]);
    } catch (\Throwable $th) {  
        echo json_encode(["error" => $th->getMessage()]);
    }
}
