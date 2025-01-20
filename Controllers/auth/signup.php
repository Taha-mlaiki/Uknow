<?php
require_once "../../vendor/autoload.php";
use Classes\User as UserClass;
use Models\User as UserModal;

require_once "../../helpers/validateInput.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    $username = validateInput($data['username']) ?? '';
    $email =  validateInput($data['email']) ?? '';
    $password =  validateInput($data['password'])  ?? '';
    $role = (int) validateInput($data['role'])  ?? '';

    if ($role !== 1 && $role !== 2) {
        echo json_encode(['error' => 'Invalid role']);
        exit();
    }

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['error' =>  'All fields are required.']);
        exit;
    }


    try {
        $newUser = new UserClass(0, $username, $email, $password, $role, 1);
        $user = new UserModal();
        if($user->isUserExists($newUser)){
            echo json_encode(['error' => 'username or email already exists.']);
            exit();
        };
        if($user->signUp($newUser)){
            echo json_encode(['success' => 'User created successfully.']);
            exit();
        }
    } catch (\Throwable $th) {
        echo json_encode(["error" => $th->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
