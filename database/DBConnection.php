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
}