<?php

namespace Models;

require_once "../../helpers/userInterface.php";
require_once "../../vendor/autoload.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use PDO;
use Classes\User as UserClass;
use Classes\Course as classCourse;
use Classes\Tag;
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
        $sql = "SELECT u.*, u.id AS user_id FROM users u JOIN role ON u.role_id = role.id WHERE role.name = 'teacher'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $teachers = [];
        foreach ($result as $teacher) {
            $teachers[] = new UserClass($teacher['user_id'], $teacher['username'], $teacher['email'], "", $teacher['role_id'], $teacher['isActive']);
        }
        return $teachers;
    }
    public function updateTeacherStatus(int $teacherId, int $status): bool
    {
        $sql = 'UPDATE users SET isActive = :status WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $teacherId, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (\Throwable $e) {
            throw new \Error('Failed to update teacher status: ' . $e->getMessage(), 0, $e);
        }

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

    public static function getStudentsCount($teacherId)
    {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(DISTINCT e.user_id) AS student_count
        FROM enrollement e
        JOIN course c ON e.course_id = c.id
        WHERE c.user_id = :teacher_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":teacher_id", $teacherId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function courseCount($user_id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM course WHERE course.user_id = :user_id");
        $stmt->bindValue(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function getMyUsers($teacherId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT u.*,u.id AS 
        user_id FROM users u
        JOIN enrollement e ON e.user_id = u.id 
        JOIN course c ON e.course_id = c.id 
        WHERE c.user_id = :teacherId 
        ");
        $stmt->bindValue(":teacherId", $teacherId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $students = [];
        foreach ($result as $student) {
            $students[] = new UserClass($student['user_id'], $student['username'], $student['email'], "", $student['role_id'], $student['isActive']);
        }
        return $students;
    }

    public static function get3RecentCourses($teacherId)
    {
        $db = Database::getConnection();
        $sql = "SELECT 
                c.id AS course_id,
                c.thumbnail,
                c.title,
                c.description,
                c.created_at,
                cat.name AS category_name,
                GROUP_CONCAT(t.name) AS tags
            FROM course c
            JOIN category cat ON c.category_id = cat.id
            LEFT JOIN course_tag ct ON c.id = ct.course_id
            LEFT JOIN tag t ON ct.tag_id = t.id
            WHERE c.user_id = :teacher_id
            GROUP BY c.id, cat.name
            ORDER BY c.created_at DESC
            LIMIT 3";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":teacher_id", $teacherId);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $coursesArray = [];
        foreach ($courses as $course) {
            $tags = [];
            foreach(explode(",",$course["tags"]) as $tag ){
                $tags[] = new Tag(0,$tag);
            }
            $coursesArray[] = new classCourse(
                $course["course_id"],
                $course["title"],
                $course["description"],
                $course["thumbnail"],
                "",
                "",
                0,
                $course["category_name"],
                0,
                $tags,
                $course["created_at"],
                "",
                ""
            );
        }
        return $coursesArray;
    }

    public static function isActive($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT isActive FROM users WHERE id = :id");
        $stmt->bindValue(":id", $userId);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    }

   
}
