<?php
require_once "../../vendor/autoload.php";

use Models\User ;

if (isset($_POST["signout"])) {
    echo "Ihere";
    $user = new User();
    $user->logout();
    header("location: http://www.localhost/uknow/pages");
}
