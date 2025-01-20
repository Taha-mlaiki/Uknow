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
    $title = $_POST["title"];
    $description = $_POST["description"];
    $document = $_POST["document"] ?? '';
    $categoryId = $_POST["category"] ?? '';
    $tags = json_decode($_POST["tags"]);

    // check the inputs

    if (!$title || !$description || !$categoryId) {
        echo json_encode(["error" => "All fields are required"]);
    }
    // upload the thumbainl image
    try {
        $thumbFileName = uploadThumb($_FILES);
    } catch (Error $e) {
        echo json_encode(["error" => "Thumbnail upload failed: " . $e->getMessage()]);
        exit();
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
    // create the ourse
    $newCourse = new CourseClass(0,$title,$description,$thumbFileName,$videoFileName ? $videoFileName:"",$document,$categoryId,"",$teacher["id"],$TagCont,"","","");
    $courseAction = new CourseModel();
    if($courseAction->createCourse($newCourse)){
        echo json_encode(["success"=> "Course created successfully"]);
        exit();
    }else {
        echo json_encode(["error"=> "Somthing went wrong"]);
        exit();
    }
}
