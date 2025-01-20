<?php
header("Content-Type: application/json");
require_once "../../vendor/autoload.php";
require_once "../../helpers/uploadThumb.php";
require_once "../../helpers/uploadVideo.php";

use Classes\Course as CourseClass;
use Models\Course as CourseModel;
use Models\Teacher as TeacherModels ;
use Classes\Tag as TagControlle;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST["courseId"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $document = $_POST["document"] ?? '';
    $categoryId = $_POST["category"] ?? '';
    $tags = json_decode($_POST["tags"]);
    $thumbFileName = null;
    // check the inputs

    if (!$title || !$description || !$categoryId|| !$tags) {
        echo json_encode(["error" => "All fields are required"]);
    }
    // upload the thumbainl image
    if(isset($_FILES["thumbnail"])){
        try {
            $thumbFileName = uploadThumb($_FILES);
        } catch (Error $e) {
            echo json_encode(["error" => "Thumbnail upload failed: " . $e->getMessage()]);
            exit();
        }
    }
    $videoFileName = null ;
    // upload the video if exists
    if(isset($_FILES["video"])){
        $videoFileName = uploadVideo($_FILES);
    }


    // check if the user is a teacher
    $newTeacher = new TeacherModels();
    $teacher = $newTeacher->isATeacher();

    if(!$teacher["exist"]){
        echo json_encode(["error" => "You are not allowed to this action."]);
        exit();
    }

    // create an array of tag object
    $TagCont = [];
     foreach($tags as $tag){
        $TagCont[] = new TagControlle($tag,"");
    }
    try {
        $newCourse = new CourseClass($courseId,$title,$description,$thumbFileName ? $thumbFileName:"",$videoFileName ? $videoFileName:"",$document,$categoryId,"",$teacher["id"],$TagCont,"","","");
        if(CourseModel::updateCourse($newCourse)){
            echo json_encode(["success"=> "Course updated successfully"]);
            exit();
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
    // create the ourse
}
