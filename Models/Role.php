<?php

namespace Models;

use Models\Database;
use Classes\Role as RoleClass;
class Role
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    public function getRoleById($id)
    {
        $sql = "SELECT name FROM role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return new RoleClass($id, $result['name']);
    }
}
