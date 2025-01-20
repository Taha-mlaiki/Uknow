<?php

namespace Models;
session_start();
use classes\User as UserClass;
use Models\Database;

class User 
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function signUp(UserClass $user)
    {
        // Ensure you're using the correct column name
        $sql = "INSERT INTO users (username, email, password_hash, role_id) 
                    VALUES (:username, :email, :password_hash, :role_id)";

        // Get user details
        $password = $user->getPassword();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $role_id = $user->getRoleId();

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':role_id', $role_id);

        // Execute the query
        $stmt->execute();

        return $stmt->rowCount() >= 1;
    }


    public function isUserExists(UserClass $user)
    {
        $sql = "SELECT * FROM users WHERE email = :email OR username = :username";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function login(UserClass $user)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            if (password_verify($user->getPassword(), $result['password_hash'])) {
                return new UserClass($result['id'], $result['username'], $result['email'],'', $result['role_id'], $result['isActive']);
            }
        }
        return false;
    }

    public function setSession(UserClass $user)
    {
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role_id' => $user->getRoleId(),
            'is_active' => $user->getIsActive()
        ];
    }

    public function logout()
    {
        session_destroy();
    }
}
