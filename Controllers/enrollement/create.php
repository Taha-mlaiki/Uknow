<?php
require_once "../../vendor/autoload.php";

use Models\Enrollment;
use Models\Student;
use Classes\Enrollment as EnrollmentClass;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST["userId"] ?? null;
    $courseId = $_POST["courseId"] ?? null;

    if (!$userId) {
        header("location: /uknow/pages/login.php");
        exit();
    }

    if (!is_numeric($courseId)) {
        header("location: /uknow/pages/");
        exit();
    }

    $userActive = Student::isActive($userId);  
    if (!$userActive) {
        header("location: /uknow/pages/activeAccount.php");
        exit();
    }

    try {
        $newEnrollement = new EnrollmentClass($userId, $courseId, "");
        $insertdEnrollement = new Enrollment();

        if ($insertdEnrollement->createEnrollment($newEnrollement)) {
            header("location: /uknow/pages/courseDetails.php?id=$courseId");
            exit();
        } else {
            header("location: /uknow/pages/");
            exit();
        }
    } catch (\Throwable $th) {
        error_log($th->getMessage()); 
        header("location: /uknow/pages/");
        exit();
    }
}

