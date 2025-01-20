<?php

namespace Models;

use Classes\Category as CategoryClass;

class Category
{

    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function getCategories()
    {
        $sql = "SELECT * FROM category";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $categories = [];
        foreach ($result as $category) {
            $categories[] = new CategoryClass($category['id'], $category['name']);
        }
        return $categories;
    }

    public function  checkExistence(CategoryClass $category)
    {
        $sql = "SELECT * FROM category WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $category->getName());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function createCategory(CategoryClass $category)
    {
        $sql = "INSERT INTO category (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $category->getName());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function updateCategory(CategoryClass $category)
    {
        $sql = "UPDATE category SET name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $category->getName());
        $stmt->bindValue(":id", $category->getId());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
    public function deleteCategory(CategoryClass $category)
    {
        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $category->getId());
        $stmt->execute();
        return $stmt->rowCount() >= 1;
    }
}
