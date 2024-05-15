<?php
namespace database;
use mysqli;

class DBConnection
{
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "todo";

    protected mysqli $connection;


    public function __construct()
    {
       $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }


    public function getHost(): string
    {
        return $this->host;
    }

    // Getter method for username
    public function getUsername(): string
    {
        return $this->username;
    }

    // Getter method for password
    public function getPassword(): string
    {
        return $this->password;
    }

    // Getter method for database
    public function getDatabase(): string
    {
        return $this->database;
    }
}