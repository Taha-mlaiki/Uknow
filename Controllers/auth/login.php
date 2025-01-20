<?php
require_once "../../vendor/autoload.php";
use Classes\User as UserClass;
use Models\User as User;

require_once "../../helpers/validateInput.php";


header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $email = validateInput($data['email'])  ?? '';
    $password =  validateInput($data['password']) ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['error' =>  'All fields are required.']);
        exit;
    }
    try {
        $loginUser = new UserClass(0, '', $email, $password, 0, 0);
        $user = new User();
        if ($user->login($loginUser)) {
            $logedUser = $user->login($loginUser);
            $user->setSession($logedUser);
            echo json_encode(['success' => 'Login successful.']);
            exit();
        }else {
            echo json_encode(['error' => 'Invalid email or password.']);
            exit();
        }
    } catch (\Throwable $th) {
        echo json_encode(["success" => $th]);
    }
}
