<?php
session_start();

$role = isset($_SESSION["user"]['role_id']) ? $_SESSION["user"]['role_id'] : null;

if(!$role){
    header("location: /uknow/pages/login.php");
    exit();
}

if($role != 3){
    header("location: /uknow/pages/forbidden.php");
    exit();
}
