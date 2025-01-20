<?php

namespace Models;

use Classes\Course as CourseClass;
use Classes\Tag as TagClass;
use Models\Database;
use Models\Teacher as Teacher;
use PDO;

class Course
{

    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAllCourses()
    {
        $sql = "SELECT c.*, cat.name AS category_name , t.name AS tag_name , t.id AS tag_id FROM course c JOIN category cat ON c.category_id = cat.id JOIN course_tag ct ON c.id = ct.course_id JOIN tag t ON ct.tag_id = t.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $courses = [];

        foreach ($result as $course) {
            $courseId = $course["id"];
            if (!isset($courses[$courseId])) {
                $courses[$courseId] = new CourseClass(
                    $course["id"],
                    $course["title"],
                    $course["description"],
                    $course["thumbnail"],
                    $course["video"],
                    $course["document"],
                    $course["category_id"],
                    $course["category_name"],
                    $course["user_id"],
                    [],
                    $course["created_at"],
                    "",
                    ""
                );
            }
            $courses[$courseId]->setTag(new TagClass($course["tag_id"], $course["tag_name"]));
        }
        return $courses;
    }

    public function createCourse(CourseClass $course)
    {
        $db = $this->db;
        try {
            // Insert course into database
            $sql = "INSERT INTO course (title, description, thumbnail, video, document, category_id, user_id) 
                    VALUES (:title, :description, :thumbnail, :video, :document, :category_id, :user_id)";
            $stmt = $db->prepare($sql);

            // Bind values
            $stmt->bindValue(":title", $course->getTitle());
            $stmt->bindValue(":description", $course->getDescription());
            $stmt->bindValue(":thumbnail", $course->getThumbnail());
            $stmt->bindValue(":video", $course->getVideo() ?? null);
            $stmt->bindValue(":document", $course->getDocument() ?? null);
            $stmt->bindValue(":category_id", $course->getCategoryId());
            $stmt->bindValue(":user_id", $course->getUserId());

            // Execute statement
            $stmt->execute();

            // Check if course was inserted
            if ($stmt->rowCount() > 0) {
                $courseId = $db->lastInsertId();

                // Insert tags associated with the course
                $tags = $course->getTags();
                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        // Check if the tag exists in the tag table
                        $sqlTagCheck = "SELECT id FROM tag WHERE id = :tag_id";
                        $stmtTagCheck = $db->prepare($sqlTagCheck);
                        $stmtTagCheck->bindValue(":tag_id", $tag->getId());
                        $stmtTagCheck->execute();

                        // If the tag doesn't exist, you may choose to insert it or skip it
                        if ($stmtTagCheck->rowCount() > 0) {
                            // If the tag exists, proceed to insert into course_tag
                            $sqlTag = "INSERT INTO course_tag (course_id, tag_id) VALUES (:course_id, :tag_id)";
                            $stmtTag = $db->prepare($sqlTag);
                            $stmtTag->bindValue(":course_id", $courseId);
                            $stmtTag->bindValue(":tag_id", $tag->getId());
                            $stmtTag->execute(); // Execute the tag insertion

                        } else {
                            throw new \InvalidArgumentException("Tag with ID " . $tag->getId() . " does not exist.");
                        }
                    }
                    return true;
                }
            }
        } catch (\Throwable $th) {
            // Improved error message for debugging
            throw new \Exception("Something went wrong with creating Course: " . $th->getMessage());
        }
    }

    public static function updateCourse(CourseClass $course)
    {
        $db = Database::getConnection();


        try {
            $db->beginTransaction();  // Start transaction

            // Delete existing tags
            $sql = "DELETE FROM course_tag WHERE course_id = :course_id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":course_id", $course->getId());
            $stmt->execute();

            // Update course details
            $sql = "UPDATE course SET title = :title, description = :description, document = :document, category_id = :category_id";
            if ($course->getVideo()) {
                $sql .= ", video = :video";
            }
            if ($course->getThumbnail()) {
                $sql .= ", thumbnail = :thumbnail";
            }

            $sql .= " WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":title", $course->getTitle());
            $stmt->bindValue(":description", $course->getDescription());
            $stmt->bindValue(":document", $course->getDocument());
            $stmt->bindValue(":category_id", $course->getCategoryId());
            $stmt->bindValue(":id", $course->getId());

            if ($course->getVideo()) {
                $stmt->bindValue(":video", $course->getVideo());
            }
            if ($course->getThumbnail()) {
                $stmt->bindValue(":thumbnail", $course->getThumbnail());
            }

            $stmt->execute();

            // Insert tags if course was updated
            if ($stmt->rowCount() > 0) {
                $courseId = $course->getId();
                $tags = $course->getTags();

                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        $sqlTagCheck = "SELECT id FROM tag WHERE id = :tag_id";
                        $stmtTagCheck = $db->prepare($sqlTagCheck);
                        $stmtTagCheck->bindValue(":tag_id", $tag->getId());
                        $stmtTagCheck->execute();

                        if ($stmtTagCheck->rowCount() > 0) {
                            $sqlTag = "INSERT INTO course_tag (course_id, tag_id) VALUES (:course_id, :tag_id)";
                            $stmtTag = $db->prepare($sqlTag);
                            $stmtTag->bindValue(":course_id", $courseId);
                            $stmtTag->bindValue(":tag_id", $tag->getId());
                            $stmtTag->execute();
                        } else {
                            throw new \Exception("Tag with ID " . $tag->getId() . " does not exist.");
                        }
                    }
                }
                $db->commit();
                return true;
            }
            $db->rollBack();
            return false;
        } catch (\PDOException $e) {
            $db->rollBack();
            throw new \Exception("Database error: " . $e->getMessage());
        } catch (\Throwable $th) {
            $db->rollBack();
            throw new \Exception("Something went wrong with updating Course: " . $th->getMessage());
        }
    }


    public static function deleteCourse($courseId)
    {
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Delete the course
            $sql = "DELETE FROM course WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $courseId);
            $stmt->execute();

            // Check if the course was deleted
            if ($stmt->rowCount() > 0) {
                // Delete associated tags
                $sql = "DELETE FROM course_tag WHERE course_id = :course_id";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(":course_id", $courseId);
                $stmt->execute();

                $db->commit();
                return true;
            } else {
                // If no rows affected, roll back transaction
                $db->rollBack();
                return false;
            }
        } catch (\Throwable $th) {
            $db->rollBack();
            throw new \Error("Error deleting course: " . $th->getMessage());
        }
    }


    public static function  getCoursesByUserId($userId)
    {
        $db = Database::getConnection();
        $sql = "SELECT 
                    c.*, 
                    c.id AS course_id, 
                    cat.name AS category_name, 
                    cat.id AS category_id, 
                    t.id AS tag_id, 
                    t.name AS tag_name 
                FROM 
                    course c 
                JOIN 
                    category cat 
                    ON c.category_id = cat.id 
                LEFT JOIN 
                    course_tag ct 
                    ON c.id = ct.course_id 
                LEFT JOIN 
                    tag t 
                    ON ct.tag_id = t.id 
                WHERE 
                    c.user_id = :user_id
            ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":user_id", $userId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $courses = [];
        foreach ($result as $course) {
            $courseId = $course["course_id"];
            if (!isset($courses[$courseId])) {
                $courses[$courseId] = new CourseClass(
                    $course["course_id"],
                    $course["title"],
                    $course["description"],
                    $course["thumbnail"],
                    $course["video"],
                    $course["document"],
                    $course["category_id"],
                    $course["category_name"],
                    $course["user_id"],
                    [],
                    $course["created_at"],
                    "",
                    ""
                );
            }
            if (!empty($course["tag_id"]) && !empty($course["tag_name"])) {
                $tag = new TagClass($course["tag_id"], $course["tag_name"]);
                $courses[$courseId]->setTag($tag);
            }
        }
        return $courses;
    }
    public static function getCourseById($courseId)
    {
        $isATeacher = Teacher::isATeacher()["exist"];
        if (!$isATeacher) {
            throw new \Exception("You are not a teacher");
            exit();
        }
        $teacherId = Teacher::isATeacher()["id"];
        $db = Database::getConnection();
        $sql = "SELECT 
                    c.*, 
                    c.id AS course_id, 
                    cat.name AS category_name, 
                    cat.id AS category_id, 
                    t.id AS tag_id, 
                    t.name AS tag_name 
                FROM 
                    course c 
                JOIN 
                    category cat 
                    ON c.category_id = cat.id 
                LEFT JOIN 
                    course_tag ct 
                    ON c.id = ct.course_id 
                LEFT JOIN 
                    tag t 
                    ON ct.tag_id = t.id 
                WHERE c.id = :course_id AND c.user_id = :teacher_id
            ";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":course_id", $courseId, PDO::PARAM_INT);
        $stmt->bindValue(":teacher_id", $teacherId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $courses = [];
        foreach ($result as $course) {
            $courseId = $course["course_id"];
            if (!isset($courses[$courseId])) {
                $courses[$courseId] = new CourseClass(
                    $course["course_id"],
                    $course["title"],
                    $course["description"],
                    $course["thumbnail"],
                    $course["video"],
                    $course["document"],
                    $course["category_id"],
                    $course["category_name"],
                    $course["user_id"],
                    [],
                    $course["created_at"],
                    "",
                    ""
                );
            }
            if (!empty($course["tag_id"]) && !empty($course["tag_name"])) {
                $tag = new TagClass($course["tag_id"], $course["tag_name"]);
                $courses[$courseId]->setTag($tag);
            }
        }
        return $courses;
    }

    public static function get4PublishedCoures()
    {

        $db = Database::getConnection();
        $sql = "SELECT 
                    c.*,
                    c.id AS course_id,
                    cat.name AS category_name,
                    GROUP_CONCAT(t.name) AS tags,
                    u.username AS user_name,
                    u.email AS user_email
                FROM course c
                JOIN category cat ON c.category_id = cat.id 
                JOIN users u ON c.user_id = u.id
                LEFT JOIN course_tag ct ON c.id = ct.course_id 
                LEFT JOIN tag t ON ct.tag_id = t.id 
                WHERE c.isPublished = 1 
                GROUP BY c.id, cat.name, u.username, u.email
                ORDER BY c.created_at DESC 
                LIMIT 6";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $courses = [];
        foreach ($result as $course) {
            $tags = [];
            if (!empty($course->tags)) {
                foreach (explode(",", $course->tags) as $tag) {
                    $tags[] = new TagClass(0, $tag);
                }
            }
            $courses[] = new CourseClass(
                $course->course_id,
                $course->title,
                $course->description,
                $course->thumbnail,
                $course->video,
                $course->document,
                $course->category_id,
                $course->category_name,
                $course->user_id,
                $tags,
                $course->created_at,
                $course->user_name,
                $course->user_email
            );
        }
        return $courses;
    }

    static public function getAllPublishedCourses($page, $limit, $searchQuery = null)
    {
        $db = Database::getConnection();
        $offset = ($page - 1) * $limit;
        
        // Base query
        $sql = "SELECT 
            c.*, 
            c.id AS course_id,
            cat.name AS category_name,
            GROUP_CONCAT(t.name) AS tags,
            u.username AS user_name,
            u.email AS user_email
            FROM course c
            JOIN category cat ON c.category_id = cat.id 
            JOIN users u ON c.user_id = u.id
            LEFT JOIN course_tag ct ON c.id = ct.course_id 
            LEFT JOIN tag t ON ct.tag_id = t.id 
            WHERE c.isPublished = 1";
    
        // Add search condition if search term is provided
        if (!empty($searchQuery)) {
            $searchTerm = "%{$searchQuery}%";
            $sql .= " AND (
                c.title LIKE :search 
                OR c.description LIKE :search 
                OR cat.name LIKE :search 
                OR t.name LIKE :search
            )";
        }
    
        // Complete the query with grouping and limits
        $sql .= " GROUP BY c.id, cat.name, u.username, u.email
                  ORDER BY c.created_at DESC 
                  LIMIT :offset, :limit";
    
        // Prepare and execute the query
        $stmt = $db->prepare($sql);
        
        // Bind search parameter if it exists
        if (!empty($searchQuery)) {
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Count total records with search
        $countSql = "SELECT COUNT(DISTINCT c.id) 
                     FROM course c
                     JOIN category cat ON c.category_id = cat.id 
                     JOIN users u ON c.user_id = u.id
                     LEFT JOIN course_tag ct ON c.id = ct.course_id 
                     LEFT JOIN tag t ON ct.tag_id = t.id 
                     WHERE c.isPublished = 1";
    
        if (!empty($searchQuery)) {
            $countSql .= " AND (
                c.title LIKE :search 
                OR c.description LIKE :search 
                OR cat.name LIKE :search 
                OR t.name LIKE :search
            )";
        }
    
        $countStmt = $db->prepare($countSql);
        if (!empty($searchQuery)) {
            $countStmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalRecords = $countStmt->fetchColumn();
    
        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);
    
        return [
            "courses" => $courses,
            "totalRecords" => $totalRecords,
            "totalPages" => $totalPages,
            "currentPage" => $page
        ];
    }

    static public function getCourseDetails($courseId, $userId = null) {
        $db = Database::getConnection();
    
        $fields = "
            c.title,
            c.description,
            c.id AS course_id,
            cat.name AS category_name,
            u.username AS user_name,
            GROUP_CONCAT(t.name) AS tags";
    
        if ($userId) {
            $enrollmentQuery = "
                SELECT COUNT(*) AS userEnrolled 
                FROM enrollement 
                WHERE user_id = :user_id AND course_id = :course_id
            ";
            $stmt = $db->prepare($enrollmentQuery);
            $stmt->bindValue(":user_id", $userId);
            $stmt->bindValue(":course_id", $courseId);
            $stmt->execute();
            $isEnrolled = $stmt->fetchColumn() > 0;
    
            if ($isEnrolled) {
                $fields .= ",
                    c.video AS course_video,
                    c.document AS course_document";
            }
        }
    
        $sql = "
            SELECT $fields
            FROM course c
            JOIN category cat ON cat.id = c.category_id
            JOIN users u ON c.user_id = u.id
            LEFT JOIN course_tag ct ON ct.course_id = c.id
            LEFT JOIN tag t ON t.id = ct.tag_id
            WHERE c.id = :id
            GROUP BY c.id
        ";
    
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $courseId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$result) {
            throw new \Exception("Course not found");
        }
    

        $tags = [];
        if (!empty($result["tags"])) {
            foreach (explode(",", $result["tags"]) as $tag) {
                $tags[] = new TagClass(0, trim($tag));
            }
        }
    
       
        return new CourseClass(
            $result["course_id"],
            $result["title"],
            $result["description"],
            "",
            $result["course_video"] ?? "",
            $result["course_document"] ?? "",
            0,
            $result["category_name"],
            0,
            $tags,
            "",
            $result["user_name"],
            ""
        );
    }
}
