<?php
namespace Models;

use Classes\Enrollment as EnrollmentClass;
use Models\Database;
use Classes\Tag as TagClass;
use Classes\Course as CourseClass;
use PDO;

class Enrollment
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function createEnrollment(EnrollmentClass $enrollment)
    {
        try {
            $studentId = $enrollment->getStudentId();
            $sql = "SELECT COUNT(*) FROM users WHERE id = :userId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":userId", $studentId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
                exit();
            }

            $courseId = $enrollment->getCourseId();
            $sql = "SELECT COUNT(*) FROM course WHERE id = :course_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":course_id", $courseId);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return false;
                exit();
            }

            $sql = "INSERT INTO enrollement (user_id, course_id) VALUES (:student_id, :course_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":student_id", $enrollment->getStudentId());
            $stmt->bindValue(":course_id", $enrollment->getCourseId());
            $stmt->execute();
            return $stmt->rowCount() >= 1;
        } catch (\PDOException $e) {
            echo "Database error: " . $e->getMessage();
            return false;
        }
    }
    public function getEnrolledCourseByUserId(EnrollmentClass $enrollment)
    {
        $sql = "SELECT c* FROM course c JOIN users u ON u.id = c.user_id JOIN enrollment e ON c.id = e.course_id WHERE e.user_id = :student_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":student_id", $enrollment->getStudentId());
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getAllCourseEnrolledByUserId($userid)
{
    try {
        $db = Database::getConnection();
        $sql = "SELECT 
                    c.*,
                    c.id AS course_id,
                    cat.name AS category_name,
                    GROUP_CONCAT(t.name) AS tags,
                    u.username AS user_name,
                    u.email AS user_email
                FROM course c
                JOIN enrollement e ON e.course_id = c.id
                JOIN category cat ON c.category_id = cat.id 
                JOIN users u ON c.user_id = u.id
                LEFT JOIN course_tag ct ON c.id = ct.course_id 
                LEFT JOIN tag t ON ct.tag_id = t.id 
                WHERE c.isPublished = 1 AND e.user_id = :userId
                GROUP BY c.id, cat.name, u.username, u.email
                ORDER BY c.created_at DESC 
                LIMIT 6";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':userId', $userid, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        return [];
    }
}

}
