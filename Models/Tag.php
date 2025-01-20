<?php
namespace Models;
require_once "../../vendor/autoload.php";
use PDO;
use Models\Database;
use Classes\Tag as TagClass;

class Tag {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getTags() {
        $sql = "SELECT * FROM tag";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tagsArray = [];
        foreach ($result as $tag) {
            $tagsArray[] = new TagClass($tag["id"], $tag["name"]);
        }
        return $tagsArray;
    }
    public function createTag(TagClass $tag) {
        $sql = "INSERT INTO tag (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $tag->getName());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function updateTag(TagClass $tag) {
        $sql = "UPDATE tag SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $tag->getName());
        $stmt->bindValue(":id", $tag->getId());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function deleteTag (TagClass $tag) {
        $sql = "DELETE FROM tag WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $tag->getId());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function checkExistence (TagClass $tag){
        $sql = "SELECT * FROM tag WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $tag->getName());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
}