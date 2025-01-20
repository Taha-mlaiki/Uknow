<?php

namespace Models;

use PDO;
use PDOException;

class Database
{
    private static $host = "localhost";
    private static $dbName = "uknow";
    private static $username = "root";
    private static $password = "";
    private static  $instance = null;

    public static function getConnection()
    {
        if (self::$instance == null) {
            try {
                $host = self::$host;
                $dbName = self::$dbName;
                $username = self::$username;
                $password = self::$password;

                $dsn = "mysql:host={$host};port=3308;dbname={$dbName}";
                self::$instance = new PDO($dsn, $username, $password);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
