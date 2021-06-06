<?php

class DatabaseConnection {

    private static $instance = null;
    private static $connection;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function getInstance() {

        if(is_null(self::$instance)) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public static function connect($host, $dbName, $user, $password) {
        self::$connection = new PDO("mysql:host=$host; dbname=$dbName; charset=utf8", $user, $password);
    }

    public static function getConnection() {
        return self::$connection;
    }

}