<?php
class Connection
{
    private static $instance;

    public static function getInstance(): PDO
    {
        if (!self::$instance) {
            $host = $_ENV['DB_HOST'] ?? 'db';
            $db = $_ENV['DB_NAME'] ?? 'test_auth_db';
            $user = $_ENV['DB_USER'] ?? 'user';
            $pass = $_ENV['DB_PASS'] ?? 'pass';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $charset = 'utf8';

            $data_source_name = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];

            self::$instance = new PDO($data_source_name, $user, $pass, $options);
        }

        return self::$instance;
    }
}