<?php

namespace Models;

require_once "../../helpers/userInterface.php";
require_once "../../vendor/autoload.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use PDO;
use Classes\User as UserClass;
use Error;
use Models\isActive;
use Models\Database;

class Teacher implements isActive
{
    private $db;
    public function  __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getTeachers()
    {
        $sql = "SELECT * FROM users JOIN role ON users.role_id = role.id WHERE role.name = 'teacher'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $teachers = [];
        foreach ($result as $teacher) {
            $teachers[] = new UserClass($teacher['id'], $teacher['username'], $teacher['email'], "", $teacher['role_id'], $teacher['isActive']);
        }
        return $teachers;
    }
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET isActive = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":status", (int)$status, PDO::PARAM_INT);
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public static function isATeacher()
    {
        $db = Database::getConnection();
        $id = $_SESSION["user"]["id"];
        if ($id) {
            $sql = "SELECT COUNT(*) FROM users JOIN role ON users.role_id = role.id WHERE role.name = 'teacher' AND users.id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return ["exist" => $stmt->rowCount() > 0, "id" => $id];
        } else {
            throw new \Error("Id not exist");
        }
    }

    public static function getStudentsCount() {
        $isATeacher = Teacher::isATeacher()["exist"];
        if (!$isATeacher["exist"]) { 
            header("location: /uknow/pages/");
        }
        $db = Database::getConnection();
        $sql = "SELECT COUNT(DISTINCT enrollment.student_id) AS student_count
        FROM enrollment
        JOIN courses ON enrollment.course_id = courses.id
        WHERE courses.teacher_id = :teacher_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":teacher_id", $isATeacher["id"]);
        $stmt->execute();
        return $stmt->fetchColumn()["student_count"];
    }

    public static function isActive($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT isActive FROM users WHERE id = :id");
        $stmt->bindValue(":id",$userId);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }
}
