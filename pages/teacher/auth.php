<?php
require_once "../../vendor/autoload.php";

session_start();
use Models\Teacher ;
$role = isset($_SESSION["user"]['role_id']) ? $_SESSION["user"]['role_id'] : null;

if(!$role){
    header("location: /uknow/pages/login.php");
    exit();
}
$user_id = $_SESSION["user"]['id'];
if($role == 2){
    $isActive =  Teacher::isActive($user_id);
    if(!$isActive){
        header("location: /uknow/pages/activeAccount.php");
    }
    exit();
}else {
    header("location: /uknow/pages/forbidden.php");
}
