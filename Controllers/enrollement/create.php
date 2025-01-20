<?php
require_once "../../vendor/autoload.php";

use Models\Enrollment;
use Models\Student;
use Classes\Enrollment as EnrollmentClass;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = isset($_POST["userId"]) ? $_POST["userId"] : null;
    $courseId =  isset($_POST["courseId"]) ? $_POST["courseId"] : null;
    if (!is_numeric($courseId) || !is_numeric($userId)) {
        echo "Id is not numberic";
        exit();
    }

    $userActive = Student::isActive($userId);
    if(!$userActive){
        header("location : /uknow/pages/activeAccount.php");
        exit();
    }

    try {

        $newEnrollement = new EnrollmentClass($userId, $courseId, "");
        $insertdEnrollement = new Enrollment();
        if ($insertdEnrollement->createEnrollment($newEnrollement)) {
            header("location: /uknow/pages/courseDetails.php?id=$courseId");
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
        exit();
    }
}
